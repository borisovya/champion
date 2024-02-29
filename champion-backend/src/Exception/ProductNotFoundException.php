<?php

declare(strict_types=1);

namespace App\Exception;

class ProductNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Product not found');
    }
}
