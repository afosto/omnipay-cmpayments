<?php

namespace Omnipay\CmPayments;

use Omnipay\CmPayments\Message\CompletePurchaseRequest;
use Omnipay\CmPayments\Message\FetchIssuersRequest;
use Omnipay\CmPayments\Message\FetchTransactionRequest;
use Omnipay\CmPayments\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway {
    /**
     * @return string
     */
    public function getName() {
        return 'CM Payments';
    }

    /**
     * @return array
     */
    public function getDefaultParameters() {
        return [
            'oauthConsumerKey'    => '',
            'oauthConsumerSecret' => '',
        ];
    }

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
     * @param array $parameters
     *
     * @return FetchIssuersRequest
     */
    public function fetchIssuers(array $parameters = []) {
        return $this->createRequest('\Omnipay\CmPayments\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return FetchIssuersRequest
     */
    public function fetchPaymentMethods(array $parameters = []) {
        return $this->createRequest('\Omnipay\CmPayments\Message\FetchPaymentMethodsRequest', $parameters);
    }

    /**
     * @param  array $parameters
     *
     * @return FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = []) {
        return $this->createRequest('\Omnipay\CmPayments\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * @param  array $parameters
     *
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = []) {
        return $this->createRequest('\Omnipay\CmPayments\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param  array $parameters
     *
     * @return CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = []) {
        return $this->createRequest('\Omnipay\CmPayments\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return void
     * @throws \Exception
     */
    public function refund(array $parameters = []) {
        throw new \Exception('Not implemented');
    }

    /**
     * @param array $parameters
     *
     * @return void
     * @throws \Exception
     */
    public function capture(array $parameters = []) {
        throw new \Exception('Not implemented');
    }
}