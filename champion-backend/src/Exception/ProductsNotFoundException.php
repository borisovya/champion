<?php

declare(strict_types=1);

namespace App\Exception;

class ProductsNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Some products not founded');
    }
}
