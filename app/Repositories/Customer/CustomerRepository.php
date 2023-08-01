<?php

namespace App\Repositories\Customer;

use App\Interfaces\Customer\CustomerRepositoryInterface;
use App\Models\Customer;

class CustomerRepository implements CustomerRepositoryInterface 
{
    private static $Instance;

    public static function getInstance()
    {
        if (!self::$Instance) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    public function getAllCustomers() 
    {
        return Customer::all();
    }

    public function getCustomerById($customerId) 
    {
        return Customer::findOrFail($customerId);
    }

    public function fetchCustomerById($customerId) 
    {
        return Customer::whereId($customerId)->first();
    }
}