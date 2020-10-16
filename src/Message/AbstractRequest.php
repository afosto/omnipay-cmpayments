<?php

namespace Omnipay\CmPayments\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    const SIGNATURE_METHOD = 'HMAC-SHA256';

    const VERSION = '1.0';

    const METHOD_POST = 'POST';

    const METHOD_GET = 'GET';

    /**
     * The salt for this request
     * @var string
     */
    private $nonce;

    /**
     * The timestamp for this request
     * @var integer
     */
    private $timestamp;

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
    public function getOauthConsumerKey()
    {
        return $this->getParameter('oauthConsumerKey');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setOauthConsumerKey($value)
    {
        return $this->setParameter('oauthConsumerKey', $value);
    }

    /**
     * @return string|null
     */
    public function getOauthConsumerSecret()
    {
        return $this->getParameter('oauthConsumerSecret');
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setOauthConsumerSecret($value)
    {
        return $this->setParameter('oauthConsumerSecret', $value);
    }

    public function setEndpoint($value)
    {
        return $this->setParameter('endpoint', $value);
    }


    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getParameter('endpoint');
    }


    /**
     * Send the data
     *
     * @param array $data
     *
     * @return array
     */
    public function sendRequest($data)
    {
        $response = $this->httpClient->request(
            $this->getMethod(),
            $this->getEndpoint() . $this->getUri(),
            [
                'Content-type'  => 'application/json',
                'Authorization' => "OAuth " . $this->getAuthorizationHeader(),
            ],
            ($this->getMethod() !== self::METHOD_POST ? null : json_encode($data))
        );

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Get the authorization header
     *
     * @return string
     *
     * @see https://docs.cmtelecom.com/payments/v0.2#/authentication%7Cbuilding_the_header_string
     */
    private function getAuthorizationHeader()
    {
        $header = '';
        foreach ([
                     'oauth_consumer_key'     => $this->getOauthConsumerKey(),
                     'oauth_nonce'            => $this->getNonce(),
                     'oauth_signature'        => $this->getSignature(),
                     'oauth_signature_method' => self::SIGNATURE_METHOD,
                     'oauth_timestamp'        => (string)$this->getTimestamp(),
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
    private function getSignature()
    {
        $parameters = [
            rawurlencode("oauth_consumer_key=" . $this->getOauthConsumerKey()),
            rawurlencode("oauth_nonce=" . $this->getNonce()),
            rawurlencode("oauth_signature_method=" . self::SIGNATURE_METHOD),
            rawurlencode("oauth_timestamp=" . (string)$this->getTimestamp()),
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
    private function getNonce()
    {
        if ($this->nonce === null) {
            $this->nonce = md5(openssl_random_pseudo_bytes(32));
        }

        return $this->nonce;
    }

    /**
     * @return integer
     */
    private function getTimestamp()
    {
        if ($this->timestamp === null) {
            $this->timestamp = time();
        }

        return $this->timestamp;
    }
}
