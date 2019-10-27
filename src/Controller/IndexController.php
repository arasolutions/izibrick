<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
