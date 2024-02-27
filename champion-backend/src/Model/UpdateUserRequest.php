<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateUserRequest
{
    #[NotBlank]
    #[OA\Property(example: 'test')]
    private string $telegramLogin;

    #[NotBlank]
    #[OA\Property(example: 'test')]
    private string $championPartnersLogin;

    public function getTelegramLogin(): string
    {
        return $this->telegramLogin;
    }

    public function setTelegramLogin(string $telegramLogin): UpdateUserRequest
    {
        $this->telegramLogin = $telegramLogin;

        return $this;
    }

    public function getChampionPartnersLogin(): string
    {
        return $this->championPartnersLogin;
    }

    public function setChampionPartnersLogin(string $championPartnersLogin): UpdateUserRequest
    {
        $this->championPartnersLogin = $championPartnersLogin;

        return $this;
    }
}
