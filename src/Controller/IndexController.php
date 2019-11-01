<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function ourContact()
    {
        return $this->render('bo/contact/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
