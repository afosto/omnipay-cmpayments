<?php

namespace Omnipay\CmPayments\Message;

use Guzzle\Common\Event;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest {

    const SIGNATURE_METHOD = 'HMAC-SHA256';

    const VERSION = '1.0';

    const METHOD_POST = 'POST';

    const METHOD_GET = 'GET';

    /**
     * The salt for this request
     * @var string
     */
    private $_nonce;

    /**
     * The timestamp for this request
     * @var integer
     */
    private $_timestamp;

    /**
     * Returns the request method that should be used
     * @return string
     */
    abstract public function getMethod();

    /**
     * Uri for the called method
     * @return string
     */
    abstract public function getUri();

    /**
     * @return string|null
     */
    public function getOauthConsumerKey() {
        return $this->getParameter('oauthConsumerKey');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setOauthConsumerKey($value) {
        return $this->setParameter('oauthConsumerKey', $value);
    }

    /**
     * @return string|null
     */
    public function getOauthConsumerSecret() {
        return $this->getParameter('oauthConsumerSecret');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setOauthConsumerSecret($value) {
        return $this->setParameter('oauthConsumerSecret', $value);
    }

    /**
     * @return string
     */
    public function getEndpoint() {
        return 'https://api-proxy.cmpayments.com';
    }

    /**
     * Send the data
     *
     * @param array $data
     *
     * @return AbstractResponse
     */
    public function sendRequest($data) {
        $this->httpClient->getEventDispatcher()->addListener('request.error', function (Event $event) use ($data) {
            $response = $event['response'];
            if ($response->isError()) {
                $event->stopPropagation();
            }
        });

        $httpRequest = $this->httpClient->createRequest(
            $this->getMethod(),
            $this->getEndpoint() . $this->getUri(),
            [
                'Content-type'  => 'application/json',
                'Authorization' => "OAuth " . $this->_getAuthorizationHeader(),
            ],
            ($this->getMethod() !== self::METHOD_POST ? null : json_encode($data))
        );

        return $httpRequest->send();
    }

    /**
     * Get the authorization header
     *
     * @return string
     *
     * @see https://docs.cmtelecom.com/payments/v0.2#/authentication%7Cbuilding_the_header_string
     */
    private function _getAuthorizationHeader() {
        $header = '';
        foreach ([
                     'oauth_consumer_key'     => $this->getOauthConsumerKey(),
                     'oauth_nonce'            => $this->_getNonce(),
                     'oauth_signature'        => $this->_getSignature(),
                     'oauth_signature_method' => self::SIGNATURE_METHOD,
                     'oauth_timestamp'        => (string)$this->_getTimestamp(),
                     'oauth_version'          => self::VERSION,
                 ] as $key => $value) {
            $header .= $key . '="' . $value . '", ';
        }

        return substr($header, 0, -2);
    }

    /**
     * Returns the signature
     *
     * @return string
     */
    private function _getSignature() {
        $parameters = [
            rawurlencode("oauth_consumer_key=" . $this->getOauthConsumerKey()),
            rawurlencode("oauth_nonce=" . $this->_getNonce()),
            rawurlencode("oauth_signature_method=" . self::SIGNATURE_METHOD),
            rawurlencode("oauth_timestamp=" . (string)$this->_getTimestamp()),
            rawurlencode("oauth_version=" . self::VERSION),
        ];

        if ($this->getMethod() === self::METHOD_POST) {
            $parameters = array_merge([rawurlencode(json_encode($this->getData()))], $parameters);
        }

        //Result of all parameters
        $signatureBase = $this->getMethod() . '&' . rawurlencode($this->getEndpoint() . $this->getUri()) . '&' .
            implode(rawurlencode('&'), $parameters);

        //Combination of keys
        $signingKey = rawurlencode($this->getOauthConsumerKey()) . '&' . rawurlencode($this->getOauthConsumerSecret());

        return rawurlencode(base64_encode(hash_hmac('sha256', $signatureBase, $signingKey)));
    }

    /**
     * @return string
     */
    private function _getNonce() {
        if ($this->_nonce === null) {
            $this->_nonce = md5(openssl_random_pseudo_bytes(32));
        }

        return $this->_nonce;
    }

    /**
     * @return integer
     */
    private function _getTimestamp() {
        if ($this->_timestamp === null) {
            $this->_timestamp = time();
        }

        return $this->_timestamp;
    }

}