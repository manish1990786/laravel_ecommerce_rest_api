<?php

namespace App\Repositories\Order;

use App\Interfaces\Order\OrderRepositoryInterface;
use App\Models\Order;
use Exception;
use App\Interfaces\Product\ProductRepositoryInterface;
use App\Interfaces\Customer\CustomerRepositoryInterface;


class OrderRepository implements OrderRepositoryInterface 
{
    private $productRepository;
    private $customerRepository;
    public function __construct(ProductRepositoryInterface $productRepository,CustomerRepositoryInterface $customerRepository) 
    {
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
    }

    public function getAllOrders() 
    {
        return Order::all();
    }

    public function getOrderById($orderId) 
    {
        return Order::findOrFail($orderId);
    }

    public function fetchOrderById($orderId) 
    {
        return Order::whereId($orderId)->first();
    }

    public function deleteOrder($orderId) 
    {
        Order::destroy($orderId);
    }

    public function createOrder(array $orderDetails) 
    {
        if($this->productRepository::getInstance()->fetchProductById($orderDetails['product_id'])){
            if($this->customerRepository::getInstance()->fetchCustomerById($orderDetails['customer_id'])){
                $orderDetails['product_id'] = json_encode([$orderDetails['product_id']]);
                return Order::create($orderDetails);
            }
            else {
                throw new Exception("Invalid customer! Please provide valid customer ID!",417);
            }
        } else {
            throw new Exception("Invalid product! Please provide valid product!",417);
        }
    }

    public function updateOrder($orderId, array $newDetails) 
    {
        return Order::whereId($orderId)->update($newDetails);
    }

    public function makeOrderPayment(array $orderPaymentDetails){
        $client = new \GuzzleHttp\Client();
        $url = "https://superpay.view.agentur-loop.com/pay";
        
        $response = $client->post($url,  [
            'body' => json_encode($orderPaymentDetails)
        ]);

        $returnResponse = json_decode($response->getBody());
        if($returnResponse->message == "Payment Successful"){
            $this->updateOrder($orderPaymentDetails['order_id'],['payment_status' => 1]);
        }
        return $returnResponse->message;
    }

    public function addProductToExistingOrder($orderId, array $newDetails) 
    {        
        $orderDetail = $this->fetchOrderById($orderId);
        if($orderDetail) {
            if($orderDetail['payment_status'] == 0){
                if($this->productRepository::getInstance()->fetchProductById($newDetails['product_id'])){

                    $products = json_decode($orderDetail['product_id'],true);
                    if(!in_array($newDetails['product_id'],$products)){
                        array_push($products,$newDetails['product_id']);
                        $newDetails['product_id'] = json_encode($products);
                        return Order::whereId($orderId)->update($newDetails);
                    } else {
                        throw new Exception("Product already exists in the Order!",417);
                    }

                } else {
                    throw new Exception("Invalid product! Please provide valid product!",417);
                }
            } else {
                throw new Exception("Invalid request!.Order has already been completed!",417);
            }
        } else {
            throw new Exception("Invalid order! Please provide valid order!",417);
        }
    }

}