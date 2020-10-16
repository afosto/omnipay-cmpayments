<?php

namespace Omnipay\CmPayments\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Complete Purchase Request
 *
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{

    /**
     * @param mixed $data
     *
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $this->sendRequest($data));
    }
}
