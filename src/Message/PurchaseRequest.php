<?php
namespace Omnipay\CmPayments\Message;

/**
 * Purchase Request
 *
 * @method \Omnipay\CmPayments\Message\PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest
{

    /**
     * Return the data formatted
     * @return array
     */
    public function getData()
    {
        $data['amount'] = round($this->getAmount(), 2);
        $data['currency'] = $this->getCurrency();

        $payment = [
            'amount'          => round($this->getAmount(), 2),
            'currency'        => $this->getCurrency(),
            'payment_method'  => $this->getPaymentMethod(),
            'payment_details' => [
                'description'   => $this->getDescription(),
                'success_url'   => $this->getReturnUrl(),
                'cancelled_url' => $this->getReturnUrl(),
                'failed_url'    => $this->getReturnUrl(),
                'expired_url'   => $this->getReturnUrl(),
                'callback_url'  => $this->getNotifyUrl(),
                'consumer_name' => $this->getCard()->getBillingName(),
                'purchase_id'   => $this->getDescription(),
            ],
        ];

        if ($this->getPaymentMethod() == 'iDEAL' && $this->getIssuer()) {
            $payment['payment_details']['issuer_id'] = $this->getIssuer();
        }
        // for credit cards we have to pass allong these issuers
        if ($this->getPaymentMethod() == 'Creditcard') {
            $payment['payment_details']['issuers'] = ['MasterCard','Visa'];
        }
        $data['payments'] = [$payment];

        return $data;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return '/charges/v1';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return AbstractRequest::METHOD_POST;
    }

    /**
     * @param mixed $data
     *
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        $httpResponse = $this->sendRequest($data);

        return $this->response = new PurchaseResponse($this, $httpResponse);
    }
}
