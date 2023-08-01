<?php

namespace App\Repositories\Product;

use App\Interfaces\Product\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface 
{
    private static $Instance;

    public static function getInstance()
    {
        if (!self::$Instance) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    public function getAllProducts() 
    {
        return Product::all();
    }

    public function getProductById($productId) 
    {
        return Product::findOrFail($productId);
    }

    public function fetchProductById($productId) 
    {
        return Product::whereId($productId)->first();
    }
}