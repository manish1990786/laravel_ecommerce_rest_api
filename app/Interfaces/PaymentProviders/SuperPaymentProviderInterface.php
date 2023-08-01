<?php

    namespace App\Interfaces\PaymentProviders;

    interface SuperPaymentProviderInterface extends PaymentProviderInterface
    {
        public function makePayment(array $orderPaymentDetails);
    }

?>