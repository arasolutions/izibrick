<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\Blog;
use App\Izibrick\Command\RemoveBlogCommand;
use App\Repository\BlogRepository;
use App\Repository\PostRepository;

/**
 * Class RemoveBlogCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemoveBlogCommandHandler
{
    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    /** @var PostRepository $postRepository */
    private $postRepository;

    /**
     * RemoveBlogCommandHandler constructor.
     * @param BlogRepository $blogRepository
     * @param PostRepository $postRepository
     */
    public function __construct(BlogRepository $blogRepository, PostRepository $postRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @param RemoveBlogCommand $command
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(RemoveBlogCommand $command)
    {
        $id = $command->id;

        /** @var Blog $blog */
        $post = $this->postRepository->get($id);


        if (!$post) {
            $message = sprintf("Error removing post : ID (%d) doesn't exist", $id);
            throw new \InvalidArgumentException($message);
        }

        $this->postRepository->remove($post);
    }

}