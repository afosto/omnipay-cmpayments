<?php

namespace Omnipay\CmPayments\Message;

/**
 * Fetch Transaction Request
 *
 * @method FetchTransactionResponse send()
 */
class FetchTransactionRequest extends AbstractRequest {

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
        return '/payments/v1/' . $this->getTransactionReference();
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
     * @return FetchTransactionResponse
     */
    public function sendData($data) {
        return new FetchTransactionResponse($this, $this->sendRequest($data)->json());
    }

}