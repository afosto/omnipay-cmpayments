<?php
namespace Omnipay\CmPayments\Message;

/**
 * Purchase Request
 *
 * @method \Omnipay\CmPayments\Message\PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest {

    /**
     * Return the data formatted
     * @return array
     */
    public function getData() {
        $data['amount'] = round($this->getAmount(), 2);
        $data['currency'] = $this->getCurrency();
        $data['payment_method'] = $this->getPaymentMethod();
        $data['due_date'] = $this->getDueDate();

        $paymentDetails['expires_at'] = $this->getExpiryDate();
        if ($this->getPaymentMethod() == 'iDEAL' && $this->getIssuer()) {
            $paymentDetails['issuer_id'] = $this->getIssuer();
        }
        $paymentDetails['description'] = $this->getDescription();
        $paymentDetails['success_url'] = $paymentDetails['cancelled_url'] = $paymentDetails['failed_url'] = $paymentDetails['expired_url'] = $this->getReturnUrl();
        $paymentDetails['callback_url'] = $this->getNotifyUrl();
        $paymentDetails['consumer_name'] = $this->getCard()->getBillingName();
        //$paymentDetails['purchase_id']

        $data['payment_details'] = $paymentDetails;

        return $data;
    }

    /**
     * @return string
     */
    public function getUri() {
        return '/charges/v1';
    }

    /**
     * @return string
     */
    public function getMethod() {
        return 'POST';
    }

    /**
     * @param mixed $data
     *
     * @return PurchaseResponse
     */
    public function sendData($data) {
        return new PurchaseResponse($this, $this->sendRequest($data)->json());
    }
}