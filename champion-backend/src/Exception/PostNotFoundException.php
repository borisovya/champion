<?php

declare(strict_types=1);

namespace App\Exception;

class PostNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Post not found');
    }
}
