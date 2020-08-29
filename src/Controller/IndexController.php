<?php

namespace App\Controller;

use App\Enum\ContactSubject;
use App\Form\ContactType;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\OurContactCommand;
use App\Izibrick\CommandHandler\OurContactCommandHandler;
use FOS\UserBundle\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AccountController
 * @Route("/")
 * @package App\Controller\Admin
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/",
     *     name="index",
     *     host="%base_host%"
     * )
     */
    public function index()
    {
        return $this->render('bo/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/price",
     *     name="price"
     * )
     */
    public function price()
    {
        return $this->render('bo/price/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/features",
     *     name="features"
     * )
     */
    public function features()
    {
        return $this->render('bo/feature/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/our-contact",
     *     name="our-contact"
     * )
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function ourContact(Request $request, \Swift_Mailer $mailer)
    {
        $subject = $request->query->get('subject');
        $success = false;
        if ($request->query->has('success')) {
            $success = $request->query->get('success');
        }
        $command = new OurContactCommand($subject);
        $form = $this->createForm(ContactType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = (new \Swift_Message('Demande de contact - ' . ContactSubject::getById($command->getSubject())['label']))
                ->setFrom($_ENV['MAILER_USER'])
                ->setTo($_ENV['CONTACT_RECEIVER'])
                ->setReplyTo($command->getEmail())
                ->setBody($this->renderView(
                    'bo/contact/email.txt.twig',
                    ['command' => $command]
                ), 'text/html'
                );
            $mailer->send($message);
            $success = true;

            return $this->redirectToRoute('our-contact', array('success' => $success));

        }
        return $this->render('bo/contact/index.html.twig', [
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

    /**
     * @Route("/cgu",
     *     name="cgu"
     * )
     */
    public function cguAction()
    {
        return $this->render('bo/cgu/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/mentions-legales",
     *     name="mentions-legales"
     * )
     */
    public function mentionsLegalesAction()
    {
        return $this->render('bo/mentions-legales/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
