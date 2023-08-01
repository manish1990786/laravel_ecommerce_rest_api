<?php

namespace App\Repositories\PaymentProviders;

use App\Interfaces\PaymentProviders\SuperPaymentProviderInterface;
use App\Interfaces\Order\OrderRepositoryInterface;

use App\Models\Order;
use Exception;


class SuperPaymentProvider implements SuperPaymentProviderInterface 
{

    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository) 
    {
        $this->orderRepository = $orderRepository;
    }

    public function makePayment(array $orderPaymentDetails){
        $client = new \GuzzleHttp\Client();
        $url = "https://superpay.view.agentur-loop.com/pay";
        
        $response = $client->post($url,  [
            'body' => json_encode($orderPaymentDetails)
        ]);

        $returnResponse = json_decode($response->getBody());
        if($returnResponse->message == "Payment Successful"){
            $this->orderRepository->updateOrder($orderPaymentDetails['order_id'],['payment_status' => 1]);
        }
        return $returnResponse->message;
    }

}