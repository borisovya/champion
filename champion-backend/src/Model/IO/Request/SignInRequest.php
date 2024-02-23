<?php

declare(strict_types=1);

namespace App\Model\IO\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignInRequest
{
    #[NotBlank]
    #[OA\Property(example: 'test@ya.ru')]
    private string $username;

    #[NotBlank]
    #[OA\Property(example: '123123')]
    private ?string $password = null;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): SignInRequest
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): SignInRequest
    {
        $this->password = $password;

        return $this;
    }
}
