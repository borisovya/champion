<?php

declare(strict_types=1);

namespace App\Model\IO\Request;

use Symfony\Component\Validator\Constraints as Assert;

class SignUpRequest
{
    #[Assert\NotBlank, Assert\Email]
    private string $email;
    #[Assert\NotBlank, Assert\Length(min: 6)]
    private string $password;
    #[Assert\NotBlank, Assert\EqualTo(propertyPath: 'password', message: 'Confirm password should be equal to password field')]
    private string $confirmPassword;
    #[Assert\NotBlank]
    private string $telegramLogin;
    private ?string $championPartnersLogin;

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): SignUpRequest
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): SignUpRequest
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): SignUpRequest
    {
        $this->password = $password;

        return $this;
    }

    public function getTelegramLogin(): string
    {
        return $this->telegramLogin;
    }

    public function setTelegramLogin(string $telegramLogin): SignUpRequest
    {
        $this->telegramLogin = $telegramLogin;

        return $this;
    }

    public function getChampionPartnersLogin(): ?string
    {
        return $this->championPartnersLogin;
    }

    public function setChampionPartnersLogin(?string $championPartnersLogin): SignUpRequest
    {
        $this->championPartnersLogin = $championPartnersLogin;

        return $this;
    }
}
