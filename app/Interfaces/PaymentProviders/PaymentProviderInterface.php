<?php

    namespace App\Interfaces\PaymentProviders;

    interface PaymentProviderInterface 
    {
        public function makePayment(array $orderPaymentDetails);
    }

?>