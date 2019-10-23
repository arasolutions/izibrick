<?php

namespace App\Controller\Admin;

use App\Entity\TrackingContact;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Entity\Post;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\TrackingContactCommand;
use App\Firebrock\Command\PostCommand;
use App\Firebrock\Command\RemoveTrackingContactCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditTrackingContactCommandHandler;
use App\Firebrock\CommandHandler\EditPostCommandHandler;
use App\Firebrock\CommandHandler\RemoveTrackingContactCommandHandler;
use App\Firebrock\CommandHandler\EditHomeCommandHandler;
use App\Firebrock\CommandHandler\EditPresentationCommandHandler;
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
 * @Route("/bo-tracking-contact")
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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);

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
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
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
