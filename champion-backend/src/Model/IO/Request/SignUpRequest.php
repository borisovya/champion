<?php

declare(strict_types=1);

namespace App\Model\IO\Request;

use App\Enum\Role;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SignUpRequest
{
    #[NotBlank]
    #[Email]
    #[OA\Property(example: 'test@ya.ru')]
    private string $username;

    #[NotBlank]
    #[Length(min: 6)]
    #[OA\Property(example: '123123')]
    private ?string $password = null;

    #[NotBlank]
    #[EqualTo(propertyPath: 'password', message: 'confirm.password')]
    #[OA\Property(example: '123123')]
    private string $confirmPassword;

    #[NotBlank]
    #[OA\Property(example: 'test')]
    private string $telegramLogin;

    #[NotBlank]
    #[OA\Property(example: 'test')]
    private string $championPartnersLogin;

    /**
     * @var array<Role>
     */
    #[OA\Property(enum: ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'], example: 'ROLE_USER')]
    private array $roles = [];

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): SignUpRequest
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): SignUpRequest
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): SignUpRequest
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

    public function getChampionPartnersLogin(): string
    {
        return $this->championPartnersLogin;
    }

    public function setChampionPartnersLogin(string $championPartnersLogin): SignUpRequest
    {
        $this->championPartnersLogin = $championPartnersLogin;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): SignUpRequest
    {
        $this->roles = $roles;

        return $this;
    }
}
