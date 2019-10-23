<?php

namespace App\Controller\Admin;

use App\Entity\TrackingQuote;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\TrackingQuoteCommand;
use App\Firebrock\Command\PostCommand;
use App\Firebrock\Command\RemoveTrackingQuoteCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditTrackingQuoteCommandHandler;
use App\Firebrock\CommandHandler\EditPostCommandHandler;
use App\Firebrock\CommandHandler\RemoveTrackingQuoteCommandHandler;
use App\Firebrock\CommandHandler\EditHomeCommandHandler;
use App\Firebrock\CommandHandler\EditPresentationCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditTrackingQuoteType;
use App\Form\EditHomeType;
use App\Form\EditPostType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Repository\SiteRepository;
use App\Repository\TrackingQuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrackingQuoteController
 * @Route("/bo-tracking-quote")
 * @package App\Controller\Admin
 */
class TrackingQuoteController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var TrackingQuoteRepository */
    private $trackingQuoteRepository;

    /**
     * TrackingQuoteController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @Route("/", name="bo-tracking-quote")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boTrackingQuote(Request $request, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        return $this->render('admin/tracking/quote/index.html.twig', [
            'controller_name' => 'TrackingQuoteController',
            'site' => $site,
            'quotes' => $site->getTrackingQuotes(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/{id}/display", name="bo-tracking-display")
     * @param TrackingQuote $trackingQuote
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boDisplayTrackingQuote(TrackingQuote $trackingQuote, Request $request, EditPostCommandHandler $editPostCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

        return $this->render('admin/tracking/quote/display.html.twig', [
            'controller_name' => 'TrackingQuoteController',
            'site' => $site,
            'trackingQuote' => $trackingQuote
        ]);
    }

    /**
     * @param TrackingQuote $trackingQuote
     * @param RemoveTrackingQuoteCommandHandler $handler
     * @return Response
     *
     * @Route("/{id}/remove", name="bo-tracking-quote-remove")
     * @method ({"GET"})
     */
    public function boRemoveTrackingQuote(TrackingQuote $trackingQuote, RemoveTrackingQuoteCommandHandler $handler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
        $command = new RemoveTrackingQuoteCommand();
        $command->id = $trackingQuote->getId();

        try {
            $handler->handle($command);
            $success = true;
        } catch ( \Exception $e ) {
            $success = false;
        }

        return $this->render('admin/tracking/quote/index.html.twig', [
            'controller_name' => 'TrackingQuoteController',
            'site' => $site,
            'quotes' => $site->getTrackingQuotes(),
            'success' => $success
        ]);
    }

}
