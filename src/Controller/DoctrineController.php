<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DoctrineController
 * @package App\Controller
 *
 * @Route("/doctrine")
 */
class DoctrineController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('doctrine/index.html.twig', [
            'controller_name' => 'DoctrineController',
        ]);
    }

    /**
     * localhost:8000/doctrine/user/2
     * @Route("/user/{id}")
     */
    public function oneUser($id, UserRepository $userRepository)
    {
        /*
         * Retourne un objet User dont les attributs sont settés
         * à partir d'un select sur la table user en bdd avec une clause where sur l'id
         * ou null si l'id n'existe pas dans la table
         */
        $user = $userRepository->find($id);

        dump($user);

        return $this->render(
            'doctrine/one_user.html.twig',
            [
                'user' => $user
            ]
        );
    }
}
