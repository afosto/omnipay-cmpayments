<?php
namespace Omnipay\CmPayments\Message;

use Omnipay\Common\Issuer;
use Omnipay\Common\Message\FetchIssuersResponseInterface;

class FetchIssuersResponse extends AbstractResponse implements FetchIssuersResponseInterface {

    /**
     * Return available issuers as an associative array.
     *
     * @return \Omnipay\Common\Issuer[]
     */
    public function getIssuers() {
        //WIP
    }
}