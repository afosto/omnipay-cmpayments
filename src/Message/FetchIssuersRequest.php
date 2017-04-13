<?php

namespace Omnipay\CmPayments\Message;

class FetchIssuersRequest extends AbstractRequest {

    /**
     * @return string
     */
    public function getMethod() {
        return 'GET';
    }

    /**
     * @return string
     */
    public function getUri() {
        return 'issuers/v1/ideal';
    }

    /**
     * @return array
     */
    public function getData() {
        return [];
    }

    /**
     * @param mixed $data
     *
     * @return FetchIssuersResponse
     */
    public function sendData($data) {
        return new FetchIssuersResponse($this, $this->sendRequest($data)->json());
    }
}