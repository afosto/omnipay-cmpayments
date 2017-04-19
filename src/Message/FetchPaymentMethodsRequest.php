<?php

namespace Omnipay\CmPayments\Message;

class FetchPaymentMethodsRequest extends AbstractRequest {

    /**
     * @return string
     */
    public function getMethod() {
        return AbstractRequest::METHOD_GET;
    }

    /**
     * @return string
     */
    public function getUri() {
        return '/payment_methods/v1';
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData() {
        return [];
    }

    /**
     * @param mixed $data
     *
     * @return FetchPaymentMethodsResponse
     */
    public function sendData($data) {
        return new FetchPaymentMethodsResponse($this, $this->sendRequest($data)->json());
    }
}