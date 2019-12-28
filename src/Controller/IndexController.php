<?php

namespace App\Controller;

use App\Enum\ContactSubject;
use App\Form\ContactType;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\OurContactCommand;
use App\Izibrick\CommandHandler\OurContactCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
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
     * @param OurContactCommandHandler $handler
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function ourContact(Request $request, \Swift_Mailer $mailer)
    {
        $command = new OurContactCommand();
        $form = $this->createForm(ContactType::class, $command);
        $form->handleRequest($request);
        $success = false;
        if ($form->isSubmitted() && $form->isValid()) {

            $message = (new \Swift_Message('Demande de contact - ' . ContactSubject::getById($command->getSubject())['label']))
                ->setFrom($_ENV['MAILER_USER'])
                ->setTo($_ENV['CONTACT_RECEIVER'])
                ->setBody($this->renderView(
                    'bo/contact/email.txt.twig',
                    ['command' => $command]
                ), 'text/html'
                );
            $mailer->send($message);
            $success = true;
            $command = new OurContactCommand();
            $form = $this->createForm(ContactType::class, $command);
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
}
