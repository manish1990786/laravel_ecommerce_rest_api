<?php

namespace App\Interfaces\Order;

interface OrderRepositoryInterface 
{
    public function getAllOrders();
    public function getOrderById($orderId);
}