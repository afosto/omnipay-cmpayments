<?php

namespace Omnipay\CmPayments;

use Omnipay\CmPayments\Message\FetchPaymentMethodsRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

use Omnipay\CmPayments\Message\CompletePurchaseRequest;
use Omnipay\CmPayments\Message\FetchTransactionRequest;
use Omnipay\CmPayments\Message\PurchaseRequest;

/**
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 * @method RequestInterface acceptNotification(array $options = array())
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'CM Payments';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'oauthConsumerKey'    => '',
            'oauthConsumerSecret' => '',
            'paymentEndpoint'     => 'https://api-proxy.cmpayments.com',
            'endpoint'            => 'https://api.cmpayments.com',
        ];
    }

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

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest|FetchPaymentMethodsRequest
     */
    public function fetchIssuers(array $parameters = [])
    {
        $parameters['endpoint'] = $this->getParameter('endpoint');
        return $this->createRequest('\Omnipay\CmPayments\Message\FetchIssuersRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|FetchPaymentMethodsRequest
     */
    public function fetchPaymentMethods(array $parameters = [])
    {
        $parameters['endpoint'] = $this->getParameter('endpoint');
        return $this->createRequest(FetchPaymentMethodsRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest|FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = [])
    {
        $parameters['endpoint'] = $this->getParameter('paymentEndpoint');
        return $this->createRequest('\Omnipay\CmPayments\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        $parameters['endpoint'] = $this->getParameter('paymentEndpoint');
        return $this->createRequest('\Omnipay\CmPayments\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest|CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        $parameters['endpoint'] = $this->getParameter('paymentEndpoint');
        return $this->createRequest('\Omnipay\CmPayments\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return void
     * @throws \Exception
     */
    public function refund(array $parameters = [])
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @param array $parameters
     *
     * @return void
     * @throws \Exception
     */
    public function capture(array $parameters = [])
    {
        throw new \Exception('Not implemented');
    }
}
