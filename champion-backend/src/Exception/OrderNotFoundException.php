<?php

declare(strict_types=1);

namespace App\Exception;

class OrderNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Order not found');
    }
}
