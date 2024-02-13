<?php

declare(strict_types=1);

namespace App\Model\IO\Response;

class SignUpResponse
{
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): SignUpResponse
    {
        $this->id = $id;

        return $this;
    }
}
