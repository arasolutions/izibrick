<?php


namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\UserSite;
use App\Firebrock\Command\RegistrationCommand;
use App\Firebrock\CommandHandler\CreateUserCommandHandler;
use App\Form\RegistrationType;
use App\Helper\Stripe;
use App\Repository\InvoiceRepository;
use App\Repository\SiteRepository;
use App\Repository\UserSiteRepository;
use App\Repository\UserRepository;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class RegistrationController extends BaseController
{
    /** @var $formFactory FactoryInterface */
    private $formFactory;

    /** @var $handler CreateUserCommandHandler */
    private $handler;

    /** @var SiteRepository */
    private $siteRepository;

    /** @var UserSiteRepository */
    private $userSiteRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var InvoiceRepository */
    private $invoiceRepository;

    /**
     * RegistrationController constructor.
     * @param FactoryInterface $formFactory
     * @param CreateUserCommandHandler $handler
     * @param SiteRepository $siteRepository
     * @param UserSiteRepository $userSiteRepository
     * @param UserRepository $userRepository
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(FactoryInterface $formFactory, CreateUserCommandHandler $handler, SiteRepository $siteRepository, UserSiteRepository $userSiteRepository, UserRepository $userRepository, InvoiceRepository $invoiceRepository)
    {
        $this->formFactory = $formFactory;
        $this->handler = $handler;
        $this->siteRepository = $siteRepository;
        $this->userSiteRepository = $userSiteRepository;
        $this->userRepository = $userRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @param Request $request
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function registerAction(Request $request)
    {
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $eventDispatcher = $this->get('event_dispatcher');

        $command = new RegistrationCommand($request->get('siteId'));

        $user = $userManager->createUser();

        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $formRegister = $this->createForm(RegistrationType::class, $command);

        $formRegister->handleRequest($request);

        if ($formRegister->isSubmitted()) {
            if ($formRegister->isValid()) {
                $find=$userManager->findUserByEmail($command->getEmail());

                if($find != null){
                    return $this->render('@FOSUser/Registration/register.html.twig', array(
                        'form' => $formRegister->createView(),
                        'error' => 'Ce compte existe déjà.'
                    ));
                }

                $this->handler->handle($command, $user);
                $form = $this->formFactory->createForm();
                $form->setData($user);

                $event = new FormEvent($form, $request);
                $eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                // Affectation du site au user
                $site = $this->siteRepository->getById($command->getSiteId());
                $userSite = new UserSite($user, $site);
                $userSite = $this->userSiteRepository->save($userSite);
                $site->getUsers()->add($userSite);
                $this->siteRepository->save($site);

                if (null === $response = $event->getResponse()) {
                    //$url = $this->generateUrl('fos_user_registration_confirmed');
                    //$response = new RedirectResponse($url);
                }

                $eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                //return $response;

                // Enregistrement du compte dans Stripe
                $stripe = new Stripe();
                $customer = $stripe->createCustomer($user->getId().'-'.$user->getEmail(), $site->getProduct()->getName(), $user->getEmail());
                $userRepo = $this->userRepository->get($user->getId());
                if($userRepo){
                    $userRepo->setStripeCustomerId($customer);
                    $this->userRepository->save($userRepo);

                    // Enregistrement de la facture
                    $invoice = new Invoice($site);
                    $invoice->setFirstName($userRepo->getFirstName());
                    $invoice->setLastName($userRepo->getLastname());
                    $invoice->setInvoiceNumber('F-'.$user->getId().'-'.time());
                    $invoice->setTitle('Offre '.$site->getProduct()->getName());
                    $invoice->setDescription('Abonnement du '.date("d/m/y").' au '. date('d/m/y', strtotime('+1 month')));
                    $invoice->setQuantity('1');
                    $invoice->setUnitPrice($site->getProduct()->getPrice());
                    $invoice->setPrice($site->getProduct()->getPrice());
                    $invoice->setVatRate('20');
                    $invoice->setTotalAmount(($site->getProduct()->getPrice()) + ($site->getProduct()->getPrice()*20/100));
                    $this->invoiceRepository->save($invoice);

                    return $this->redirectToRoute('payment', array('siteId' => $site->getId(), 'userId' => $userRepo->getId()));
                }

            }

            $event = new FormEvent($formRegister, $request);
            $eventDispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $formRegister->createView()
        ));
    }

    public function checkEmailAction(Request $request)
    {
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $email = $request->getSession()->get('fos_user_send_confirmation_email/email');

        if (empty($email)) {
            return new RedirectResponse($this->generateUrl('fos_user_registration_register'));
        }

        $request->getSession()->remove('fos_user_send_confirmation_email/email');
        $user = $userManager->findUserByEmail($email);

        if (null === $user) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
        }

        return $this->render('@FOSUser/Registration/check_email.html.twig', array(
            'user' => $user,
        ));
    }
}