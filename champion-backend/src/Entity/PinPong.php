<?php

namespace App\Entity;

use App\Repository\PinPongRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PinPongRepository::class)]
class PinPong
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
