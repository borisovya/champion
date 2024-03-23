<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\UploadFileInvalidTypeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

readonly class FileService
{
    public function __construct(
        private Filesystem $fs,
        private string $uploadDir,
    ) {
    }

    public function deleteFile(string $path): void
    {
        $this->fs->remove($this->uploadDir.$path);
    }

    public function saveFile(string $path, UploadedFile $file): string
    {
        $extension = $file->guessExtension();

        if (null === $extension) {
            throw new UploadFileInvalidTypeException();
        }

        $uuid = Uuid::v4()->toRfc4122().'.'.$extension;

        $file->move(
            $this->uploadDir.DIRECTORY_SEPARATOR.$path,
            $uuid,
        );

        return DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$uuid;
    }
}
