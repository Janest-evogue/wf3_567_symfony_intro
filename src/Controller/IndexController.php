<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/templating")
     */
    public function templating()
    {
        return $this->render(
            'index/templating.html.twig',
            [
                // un objet DateTime à la date de demain :
                'demain' => new \DateTime('+1day')
            ]
        );
    }

    /*
     * Faire une page avec un Hello world,
     * choisir son URL et la tester dans le navigateur
     *
     * passer votre nom comme variable au template
     * et changer le Hello world en Hello Julien
     */

    /**
     * @Route("/hello-world")
     */
    public function hello()
    {
        return $this->render(
            'index/hello.html.twig',
            // le template index/hello.html.twig peut utiliser une variable
            // qui s'appelle nom et qui vaut Julien
            [
                'nom' => 'Julien'
            ]
        );
    }


}
