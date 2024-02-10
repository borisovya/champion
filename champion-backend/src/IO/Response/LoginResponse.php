<?php

declare(strict_types=1);

namespace App\IO\Response;

class LoginResponse
{
    public function __construct(
        private int $id
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): LoginResponse
    {
        $this->id = $id;

        return $this;
    }
}