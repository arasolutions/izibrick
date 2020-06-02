<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\CustomPage;
use App\Entity\Site;
use App\Izibrick\Command\CustomPageCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Repository\CustomPageRepository;
use App\Repository\SiteRepository;

/**
 * Class EditCustomPageCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditCustomPageCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var CustomPageRepository $customPageRepository */
    private $customPageRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository, CustomPageRepository $customPageRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->customPageRepository = $customPageRepository;
    }

    /**
     * @param CustomPageCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(CustomPageCommand $command, Site $site)
    {
        $id = $command->id;
        if (empty($id)) {
            $customPage = new CustomPage($site);
        } else {
            /** @var Post $post */
            $customPage = $this->customPageRepository->get($id);
            if (!$customPage) {
                throw new \Exception(sprintf('Error - customPage not found (id: %d)', $id));
            }
        }
        $customPage->setNameMenu($command->nameMenu);
        $customPage->setNameMenuUrl($this->format_url($command->nameMenu));
        $customPage->setContent($command->content);
        $customPage->setPlace($command->place);
        $customPage->setSeoTitle($command->seoTitle);
        $customPage->setSeoDescription($command->seoDescription);
        $this->customPageRepository->save($customPage);
    }

    function remove_accents($string)
    {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = strtr(utf8_decode($string), utf8_decode($a), $b);
        return utf8_encode($string);
    }

    function format_url($title)
    {
        $title = $this->remove_accents($title);
        $title = trim(strtolower($title));
        $title = preg_replace('#[^a-z0-9\\-/]#i', '-', $title);
        return trim(preg_replace('/-+/', '-', $title), '-/');
    }

}