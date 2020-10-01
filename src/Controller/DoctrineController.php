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

    /**
     * @Route("/users")
     */
    public function listUsers(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        // Retourne tous le contenu de la table user
        // sous la forme d'un tableau d'objets User
        dump($users);

        return $this->render(
            'doctrine/list_users.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/users/firstname/{firstname}")
     */
    public function usersByFirstname($firstname, UserRepository $userRepository)
    {
        // findBy permet de faire une requête avec une clause WHERE,
        // ici : select * from user where firstname = 'prenom_reçu_dans_l_url';
        // findBy() retourne un tableau d'objet User
        $users = $userRepository->findBy([
            'firstname' => $firstname
        ]);

        return $this->render(
            'doctrine/list_users.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/pseudo/{pseudo}")
     */
    public function userByPseudo($pseudo, UserRepository $userRepository)
    {
        // findOneBy() s'utilise quand on est sûr qu'il n'y aura
        // pas plus d'un résultat à la requête.
        // Retourne un objet User ou null
        $user = $userRepository->findOneBy([
            'pseudo' => $pseudo
        ]);

        return $this->render(
            'doctrine/one_user.html.twig',
            [
                'user' => $user
            ]
        );
    }
}
