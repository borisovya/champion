<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;

class CrudPostRequest
{
    #[NotBlank]
    #[OA\Property(example: 'post 1')]
    private string $title;

    #[OA\Property(example: 'post description')]
    private ?string $description = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
