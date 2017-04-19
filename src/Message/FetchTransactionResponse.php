<?php
namespace Omnipay\CmPayments\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class FetchTransactionResponse extends AbstractResponse implements RedirectResponseInterface {

    /**
     * {@inheritdoc}
     */
    public function isRedirect() {
        return isset($this->data['payments'][0]['payment_details']['authentication_url']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl() {
        if ($this->isRedirect()) {
            return $this->data['payments'][0]['payment_details']['authentication_url'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod() {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData() {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful() {
        return parent::isSuccessful();
    }

    /**
     * @return boolean
     */
    public function isOpen() {
        return isset($this->data['status']) && 'Open' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isCancelled() {
        return isset($this->data['status']) && 'Cancelled' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isPaid() {
        return isset($this->data['status']) && 'Success' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isExpired() {
        return isset($this->data['status']) && 'Expired' === $this->data['status'];
    }

    /**
     * @return mixed
     */
    public function getTransactionReference() {
        if (isset($this->data['payments'][0]['payment_id'])) {
            return $this->data['payments'][0]['payment_id'];
        }
    }

    /**
     * @return mixed
     */
    public function getTransactionId() {
        if (isset($this->data['payments'][0]['payment_id'])) {
            return $this->data['payments'][0]['payment_id'];
        }
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        if (isset($this->data['status'])) {
            return $this->data['status'];
        }
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        if (isset($this->data['amount'])) {
            return $this->data['amount'];
        }
    }
}