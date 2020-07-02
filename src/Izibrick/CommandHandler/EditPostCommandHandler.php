<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Entity\Post;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PostCommand;
use App\Repository\SiteRepository;
use App\Repository\PostRepository;

/**
 * Class EditPostCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPostCommandHandler
{
    /** @var PostRepository $postRepository */
    private $postRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(PostRepository $postRepository, SiteRepository $siteRepository)
    {
        $this->postRepository = $postRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param PostCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PostCommand $command, Site $site, Page $page = null)
    {
        $id = $command->id;
        if (empty($id)) {
            $post = new Post();
            $post->setBlog($site->getBlog());
        } else {
            /** @var Post $post */
            $post = $this->postRepository->get($id);
            if (!$post) {
                throw new \Exception(sprintf('Error - Post not found (id: %d)', $id));
            }
        }
        $post->setTitle($command->title);
        $post->setIntroduction($command->introduction);
        $post->setContent($command->content);
        $post->setPage($page);
        if ($command->image != null) {
            $post->setImage(null);
            $post->setImageFile($command->image);
        }
        $this->postRepository->save($post);
    }

}