<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[OA\Property(
        example: 1,
    )]
    private ?int $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    #[OA\Property(
        example: 'test@mail.ru',
    )]
    private string $username;

    #[ORM\Column(type: 'string')]
    #[OA\Property(
        example: 'test',
    )]
    #[Ignore]
    private string $password;

    #[ORM\Column(type: 'string')]
    #[OA\Property(
        example: 'test',
    )]
    private string $telegramLogin;

    #[ORM\Column(type: 'string', nullable: true)]
    #[OA\Property(
        example: 'test',
    )]
    private ?string $championPartnersLogin;

    #[ORM\Column(type: 'json')]
    #[OA\Property(
        example: ['ROLE_USER'],
    )]
    private array $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getTelegramLogin(): string
    {
        return $this->telegramLogin;
    }

    public function setTelegramLogin(string $telegramLogin): static
    {
        $this->telegramLogin = $telegramLogin;

        return $this;
    }

    public function getChampionPartnersLogin(): ?string
    {
        return $this->championPartnersLogin;
    }

    public function setChampionPartnersLogin(?string $championPartnersLogin): static
    {
        $this->championPartnersLogin = $championPartnersLogin;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
