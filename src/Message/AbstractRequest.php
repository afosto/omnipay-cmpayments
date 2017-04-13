<?php

namespace Omnipay\CmPayments\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest {

    /**
     * Key generation method
     */
    const SIGNATURE_METHOD = 'HMAC-SHA256';

    /**
     * API Oauth version
     */
    const VERSION = '1.0';

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
     * Method
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
     * @return string|null
     */
    public function getOauthConsumerSecret() {
        return $this->getParameter('oauthConsumerSecret');
    }

    /**
     * Get the authorization header
     *
     * @return string
     *
     * @see https://docs.cmtelecom.com/payments/v0.2#/authentication%7Cbuilding_the_header_string
     */
    public function getAuthorizationHeader($data) {
        $header = rawurlencode(implode(',', [
            'oauth_consumer_key'     => $this->getOauthConsumerKey(),
            'oauth_nonce'            => $this->_getNonce(),
            'oauth_signature'        => $this->_getSignature($data),
            'oauth_signature_method' => self::SIGNATURE_METHOD,
            'oauth_timestamp'        => (string)$this->_getTimestamp(),
            'oauth_version'          => self::VERSION,
        ]));

        return $header;
    }

    /**
     * @return string
     */
    public function getEndpoint() {
        return 'https://api.cmpayments.com';
    }

    /**
     * Due date is now plus 3 hours
     *
     * @return int
     */
    public function getDueDate() {
        return time() + (60 * 60 * 3);
    }

    /**
     * @return int
     */
    public function getExpiryDate() {
        return $this->getDueDate();
    }

    /**
     * Send the data
     *
     * @param array $data
     *
     * @return AbstractResponse
     */
    public function sendRequest($data) {
        $httpRequest = $this->httpClient->createRequest(
            $this->getMethod(),
            $this->getEndpoint() . '/' . $this->getUri(),
            ['Autorization' => 'Oauth ' . $this->getAuthorizationHeader($data)],
            $data);

        return $this->response = $httpRequest->send();
    }

    /**
     * Returns the signature
     *
     * @param array $data
     *
     * @return string
     */
    private function _getSignature($data) {
        //Get the fixed parts of the signature
        $signatureParts = [
            'oauth_consumer_key'     => $this->getOauthConsumerKey(),
            'oauth_nonce'            => $this->_getNonce(),
            'oauth_signature_method' => self::SIGNATURE_METHOD,
            'oauth_timestamp'        => (string)$this->_getTimestamp(),
            'oauth_version'          => self::VERSION,
        ];
        array_walk($signatureParts, function (&$value, &$key) {
            $value = rawurlencode($value);
            $key = rawurlencode($key);
        });

        //Build the parameter string
        $signatureBase = json_encode($data);
        $signatureBase .= '&' . http_build_query($signatureParts);

        //Create the signature base
        $signatureBase = $this->getMethod() . '&' . rawurlencode($this->getEndpoint() .
                $this->getUri()) . '&' . rawurlencode($signatureBase);

        return base64_encode(hash_hmac('sha256', $signatureBase, pack("H*", $this->_getSigningKey()), true));
    }

    /**
     * @return string
     */
    private function _getSigningKey() {
        return implode('&', [
            $this->getOauthConsumerKey(),
            $this->getOauthConsumerSecret(),
        ]);
    }

    /**
     * @return string
     */
    private function _getNonce() {
        if ($this->_nonce === null) {
            $this->_nonce = base64_encode(openssl_random_pseudo_bytes(32));
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