<?php

namespace App\Izibrick\CommandHandler;

use App\Entity\Site;
use App\Entity\Page;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypePresentationCommand;
use App\Repository\PageTypePresentationRepository;
use App\Repository\SiteRepository;
use App\Repository\PageRepository;

/**
 * Class AddSupportCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPageTypePresentationCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var PageRepository */
    private $pageRepository;

    /** @var PageTypePresentationRepository */
    private $pageTypePresentationRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param PageRepository $pageRepository
     * @param PageTypePresentationRepository $pageTypePresentationRepository
     */
    public function __construct(SiteRepository $siteRepository, PageRepository $pageRepository, PageTypePresentationRepository $pageTypePresentationRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->pageRepository = $pageRepository;
        $this->pageTypePresentationRepository = $pageTypePresentationRepository;
    }

    /**
     * @param AddPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PageTypePresentationCommand $command, Site $site)
    {
        $page = $this->pageRepository->get($command->id);
        if (!$page) {
            throw new \Exception(sprintf('Error - Page not found (id: %d)', $command->id));
        }
        $pageTypePresentation = $this->pageTypePresentationRepository->getByPageId($command->id);
        if (!$pageTypePresentation) {
            throw new \Exception(sprintf('Error - PageTypePresentation not found (id: %d)', $command->id));
        }
        $page->setType($command->type->getId());
        $page->setNameMenu($command->name);
        $page->setNameMenuUrl($this->cleanUrl($command->name));
        $page->setDisplayMenuHeader($command->displayMenuHeader);
        $page->setDisplayMenuFooter($command->displayMenuFooter);
        $page->setSeoTitle($command->seoTitle);
        $page->setSeoDescription($command->seoDescription);
        $this->pageRepository->save($page);
        $pageTypePresentation->setContent($command->content);
        $this->pageTypePresentationRepository->save($pageTypePresentation);
    }


    public function cleanUrl($string)
    {
        /**
         * REMOVE ACCENTS
         */
        $url = $this->removeAccents($string);

        /**
         * TO LOWERCASE
         */
        $url = strtolower($url);

        /**
         * REMOVE NON ALPHANUMERIC CHARACTER
         */
        $url = preg_replace('/[^0-9a-z\s]/', ' ', $url);

        /**
         * TRIM
         */
        $url = trim($url);

        /**
         * REPLACE MULTIPLE SPACE
         */
        $url = preg_replace('/\s\s+/', ' ', $url);

        /**
         * REPLACE SPACE BY UNDERSCORE
         */
        $url = preg_replace('/\s/', '-', $url);

        return $url;
    }

    public function removeAccents($string)
    {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );
        $string = strtr($string, $table);
        return $string;
    }

}