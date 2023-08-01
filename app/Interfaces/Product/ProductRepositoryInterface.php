<?php

namespace App\Interfaces\Product;

interface ProductRepositoryInterface 
{
    public function getAllProducts();
    public function getProductById($productId);
}