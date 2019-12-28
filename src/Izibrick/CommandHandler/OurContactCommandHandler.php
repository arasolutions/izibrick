<?php


namespace App\Izibrick\CommandHandler;


use App\Izibrick\Command\OurContactCommand;
use Symfony\Component\Mailer\Mailer;

class OurContactCommandHandler
{

    public function handle(OurContactCommand $command, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('contact@firebrock.com')
            ->setTo('arnaud.lutringer@gmail.com')
            ->setBody($this->renderView(
            // templates/emails/registration.html.twig
                'emails/registration.html.twig',
                ['name' => $name]
            ),
                'text/html'
            );
        $mailer->send($message);
        return;
    }

}