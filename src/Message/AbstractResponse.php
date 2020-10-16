<?php

namespace Omnipay\CmPayments\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{

    /**
     * Redirects can never be successful
     * @return bool
     */
    public function isSuccessful()
    {
        return !$this->isRedirect() && !isset($this->data['payment_details']['reason_for_failure']);
    }
}
