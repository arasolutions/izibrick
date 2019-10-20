<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Entity\Blog;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\BlogCommand;
use App\Repository\SiteRepository;
use App\Repository\BlogRepository;

/**
 * Class EditBlogCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class EditBlogCommandHandler
{
    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(BlogRepository $blogRepository, SiteRepository $siteRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param BlogCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(BlogCommand $command, Site $site)
    {
        $id = $command->id;
        if (empty($id)) {
            $blog = new Blog();
            $blog->setSite($site);
        } else {
            /** @var Post $post */
            $blog = $this->blogRepository->get($id);
            if (!$blog) {
                throw new \Exception(sprintf('Error - Blog not found (id: %d)', $id));
            }
        }
        $blog->setTitle($command->title);
        $blog->setIntroduction($command->introduction);
        $blog->setContent($command->content);
        if ($command->image != null) {
            $blog->setImageFile($command->image);
        }
        $this->blogRepository->save($blog);
    }

}