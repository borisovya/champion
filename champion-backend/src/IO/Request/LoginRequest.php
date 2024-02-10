<?php

declare(strict_types=1);

namespace App\IO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank, Assert\Email]
        private string $email,
        #[Assert\NotBlank]
        private string $password,
        #[Assert\NotBlank]
        private string $telegramLogin,
        private ?string $championPartnersLogin,
    )
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): LoginRequest
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): LoginRequest
    {
        $this->password = $password;

        return $this;
    }

    public function getTelegramLogin(): string
    {
        return $this->telegramLogin;
    }

    public function setTelegramLogin(string $telegramLogin): LoginRequest
    {
        $this->telegramLogin = $telegramLogin;

        return $this;
    }

    public function getChampionPartnersLogin(): ?string
    {
        return $this->championPartnersLogin;
    }

    public function setChampionPartnersLogin(?string $championPartnersLogin): LoginRequest
    {
        $this->championPartnersLogin = $championPartnersLogin;

        return $this;
    }
}