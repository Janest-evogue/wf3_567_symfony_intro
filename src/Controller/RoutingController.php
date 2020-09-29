<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoutingController
 * @package App\Controller
 *
 * L'annotation de route mise au dessus de la classe
 * défini un préfixe pour les urls des routes définies
 * dans la classe
 * @Route("/routing")
 */
class RoutingController extends AbstractController
{
    /**
     * Avec le préfixe de route, l'url est /routing/ ou /routing
     * @Route("/")
     */
    public function index()
    {
        return $this->render('routing/index.html.twig');
    }

    /**
     * {qui} est une partie variable de l'url :
     * l'url peut être /routing/bonjour/Julien
     * Le $qui en paramètre de la méthode dans ce cas vaudra Julien
     *
     * @Route("/bonjour/{qui}")
     */
    public function bonjour($qui)
    {
        return $this->render(
            'routing/bonjour.html.twig',
            [
                'nom' => $qui
            ]
        );
    }

    /**
     * L'url peut être /routing/salut/Julien
     * ou bien /routing/salut ou /routing/salut/
     *
     * S'il n'y a rien derrière le salut,
     * $qui vaut 'à toi'
     *
     * @Route("/salut/{qui}", defaults={"qui": "à toi"})
     */
    public function salut($qui)
    {
        return $this->render(
            'routing/salut.html.twig',
            [
                'qui' => $qui
            ]
        );
    }

    /**
     * Une route avec 2 parties variables dont une optionnelle
     *
     * @Route("/coucou/{prenom}-{nom}", defaults={"nom": ""})
     */
    public function coucou($prenom, $nom)
    {
        $nomComplet = trim($prenom . ' ' . $nom);

        return $this->render(
            'routing/coucou.html.twig',
            [
                'qui' => $nomComplet
            ]
        );
    }

    /**
     * id doit être un nombre (\d+ en expression régulière)
     *
     * @Route("/utilisateur/modifier/{id}", requirements={"id": "\d+"})
     */
    public function userEdit($id)
    {
        return $this->render(
            'routing/user_edit.html.twig',
            [
                'id' => $id
            ]
        );

    }
}
