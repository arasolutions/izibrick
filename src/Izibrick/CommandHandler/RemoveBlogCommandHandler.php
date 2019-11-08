<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\Blog;
use App\Izibrick\Command\RemoveBlogCommand;
use App\Repository\BlogRepository;

/**
 * Class RemoveBlogCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemoveBlogCommandHandler
{
    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param BlogRepository $blogRepository
     */
    public function __construct(BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * @param BlogCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(RemoveBlogCommand $command)
    {
        $id = $command->id;

        /** @var Blog $blog */
        $blog = $this->blogRepository->get($id);

        if (!$blog) {
            $message = sprintf("Error removing blog : ID (%d) doesn't exist", $id);
            throw new \InvalidArgumentException($message);
        }

        $this->blogRepository->remove($blog);
    }

}