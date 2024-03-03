<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Exception\PostNotFoundException;
use App\Model\PostRequest;
use App\Service\FileService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly FileService $fileService,
    ) {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws PostNotFoundException
     */
    public function findOrFail(int $postId): Post
    {
        $post = $this->find($postId);

        if (!$post) {
            throw new PostNotFoundException();
        }

        return $post;
    }

    public function remove(Post $post): void
    {
        $this->_em->remove($post);
        $this->_em->flush();
    }

    public function store(PostRequest $createPostRequest): Post
    {
        $post = (new Post())
            ->setTitle($createPostRequest->getTitle())
            ->setDescription($createPostRequest->getDescription());

        $this->_em->persist($post);
        $this->_em->flush();

        return $post;
    }

    public function reStore(Post $post, PostRequest $createPostRequest): Post
    {
        $post
            ->setTitle($createPostRequest->getTitle())
            ->setDescription($createPostRequest->getDescription());

        $this->_em->flush();

        return $post;
    }

    public function bindImage(Post $post, string $link): Post
    {
        $imagePath = $post->getImage();

        if ($imagePath) {
            $this->fileService->deleteFile($imagePath);
        }

        $post->setImage($link);
        $this->_em->flush();

        return $post;
    }
}
