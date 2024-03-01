<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue(
        strategy: 'IDENTITY'
    )]
    #[ORM\Column(
        type: 'integer'
    )]
    protected $id;

    #[ORM\Column(
        type: 'string'
    )]
    protected $refreshToken;

    #[ORM\Column(
        type: 'string'
    )]
    protected $username;

    #[ORM\Column(
        type: 'datetime'
    )]
    protected $valid;
}
