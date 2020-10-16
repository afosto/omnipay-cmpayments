<?php

namespace Omnipay\CmPayments\Message;

use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Common\PaymentMethod;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{

    /**
     * Return available payment methods as an associative array
     *
     * @return \Omnipay\Common\PaymentMethod[]
     */
    public function getPaymentMethods()
    {
        $paymentMethods = [];
        foreach ($this->data as $method => $opitons) {
            $paymentMethods[] = new PaymentMethod($method, $method);
        }

        return $paymentMethods;
    }
}
