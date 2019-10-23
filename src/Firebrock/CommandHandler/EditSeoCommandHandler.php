<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Entity\Contact;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\SeoCommand;
use App\Repository\SiteRepository;
use App\Repository\HomeRepository;
use App\Repository\PresentationRepository;
use App\Repository\BlogRepository;
use App\Repository\QuoteRepository;
use App\Repository\ContactRepository;

/**
 * Class EditSeoCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class EditSeoCommandHandler
{
    /** @var HomeRepository $homeRepository */
    private $homeRepository;

    /** @var PresentationRepository $presentationRepository */
    private $presentationRepository;

    /** @var BlogRepository $blogRepository */
    private $blogRepository;

    /** @var QuoteRepository $quoteRepository */
    private $quoteRepository;

    /** @var ContactRepository $contactRepository */
    private $contactRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(HomeRepository $homeRepository,
                                PresentationRepository $presentationRepository,
                                BlogRepository $blogRepository,
                                QuoteRepository $quoteRepository,
                                ContactRepository $contactRepository,
                                SiteRepository $siteRepository)
    {
        $this->homeRepository = $homeRepository;
        $this->presentationRepository = $presentationRepository;
        $this->blogRepository = $blogRepository;
        $this->quoteRepository = $quoteRepository;
        $this->contactRepository = $contactRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param SeoCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(SeoCommand $command, Site $site)
    {
        $id = $site->getId();
        $home = $this->homeRepository->getBySiteId($id);
        if ($home) {
            $home->setSeoTitle($command->seoTitleHome);
            $home->setSeoDescription($command->seoDescriptionHome);
            $this->homeRepository->save($home);
        }
        $presentation = $this->presentationRepository->getBySiteId($id);
        if ($presentation) {
            $presentation->setSeoTitle($command->seoTitlePresentation);
            $presentation->setSeoDescription($command->seoDescriptionPresentation);
            $this->presentationRepository->save($presentation);
        }
        $blog = $this->blogRepository->getBySiteId($id);
        if ($blog) {
            $blog->setSeoTitle($command->seoTitleBlog);
            $blog->setSeoDescription($command->seoDescriptionBlog);
            $this->blogRepository->save($blog);
        }
        $quote = $this->quoteRepository->getBySiteId($id);
        if ($quote) {
            $quote->setSeoTitle($command->seoTitleQuote);
            $quote->setSeoDescription($command->seoDescriptionQuote);
            $this->quoteRepository->save($quote);
        }
        $contact = $this->contactRepository->getBySiteId($id);
        if ($contact) {
            $contact->setSeoTitle($command->seoTitleContact);
            $contact->setSeoDescription($command->seoDescriptionContact);
            $this->contactRepository->save($contact);
        }
    }

}