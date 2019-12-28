<?php

namespace App\Controller\Admin;

use App\Entity\TrackingContact;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\TrackingContactCommand;
use App\Izibrick\Command\PostCommand;
use App\Izibrick\Command\RemoveTrackingContactCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PresentationCommand;
use App\Izibrick\CommandHandler\EditContactCommandHandler;
use App\Izibrick\CommandHandler\EditGlobalParametersCommandHandler;
use App\Izibrick\CommandHandler\EditTrackingContactCommandHandler;
use App\Izibrick\CommandHandler\EditPostCommandHandler;
use App\Izibrick\CommandHandler\RemoveTrackingContactCommandHandler;
use App\Izibrick\CommandHandler\EditHomeCommandHandler;
use App\Izibrick\CommandHandler\EditPresentationCommandHandler;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditTrackingContactType;
use App\Form\EditHomeType;
use App\Form\EditPostType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Repository\SiteRepository;
use App\Repository\TrackingContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrackingContactController
 * @Route("/admin/bo-tracking-contact")
 * @package App\Controller\Admin
 */
class TrackingContactController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var TrackingContactRepository */
    private $trackingContactRepository;

    /**
     * TrackingContactController constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @Route("/", name="bo-tracking-contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boTrackingContact(Request $request, $success = false)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        return $this->render('admin/tracking/contact/index.html.twig', [
            'controller_name' => 'TrackingContactController',
            'site' => $site,
            'contacts' => $site->getTrackingContacts(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/{id}/display", name="bo-tracking-contact-display")
     * @param TrackingContact $trackingContact
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boDisplayTrackingContact(TrackingContact $trackingContact, Request $request, EditPostCommandHandler $editPostCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);

        return $this->render('admin/tracking/contact/display.html.twig', [
            'controller_name' => 'TrackingContactController',
            'site' => $site,
            'trackingContact' => $trackingContact
        ]);
    }

    /**
     * @param TrackingContact $trackingContact
     * @param RemoveTrackingContactCommandHandler $handler
     * @return Response
     *
     * @Route("/{id}/remove", name="bo-tracking-contact-remove")
     * @method ({"GET"})
     */
    public function boRemoveTrackingContact(TrackingContact $trackingContact, RemoveTrackingContactCommandHandler $handler)
    {
        $site = $this->siteRepository->getById($_SESSION[Constants::SESSION_SITE_ID]);
        $command = new RemoveTrackingContactCommand();
        $command->id = $trackingContact->getId();

        try {
            $handler->handle($command);
            $success = true;
        } catch ( \Exception $e ) {
            $success = false;
        }

        return $this->render('admin/tracking/contact/index.html.twig', [
            'controller_name' => 'TrackingContactController',
            'site' => $site,
            'contacts' => $site->getTrackingContacts(),
            'success' => $success
        ]);
    }

}
