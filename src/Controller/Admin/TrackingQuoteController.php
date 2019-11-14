<?php

namespace App\Controller\Admin;

use App\Entity\TrackingQuote;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\TrackingQuoteCommand;
use App\Izibrick\Command\PostCommand;
use App\Izibrick\Command\RemoveTrackingQuoteCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditTrackingQuoteCommandHandler;
use App\Izibrick\CommandHandler\EditPostCommandHandler;
use App\Izibrick\CommandHandler\RemoveTrackingQuoteCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
use App\Izibrick\CommandHandler\EditPresentationCommandHandler;
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
 * @Route("/admin/bo-tracking-quote")
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
     * @Route("/{id}/display", name="bo-tracking-quote-display")
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
