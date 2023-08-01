<?php

namespace App\Interfaces\Customer;

interface CustomerRepositoryInterface 
{
    public function getAllCustomers();
    public function getCustomerById($customerId);
}