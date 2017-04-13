<?php

namespace Omnipay\CmPayments\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse {

    public function isSuccessful() {
        return !$this->isRedirect() && !isset($this->data['reason_for_failure']);
    }

}