<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HttpController
 * @package App\Controller
 *
 * @Route("/http")
 */
class HttpController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }

    /**
     * on peut utiliser un objet Request grâce à Request $request en
     * paramètre de la méthode
     *
     * @Route("/requete")
     */
    public function requete(Request $request)
    {
        // http://127.0.0.1:8000/http/requete?nom=Marx&prenom=Groucho
        dump($_GET); // ['nom' => 'Marx', 'prenom' => 'Groucho']

        // $request->query contient un objet qui est une surcouche à $_GET,
        // sa méthode all() retourne tout le contenu de $_GET
        dump($request->query->all()); // ['nom' => 'Marx', 'prenom' => 'Groucho']

        // echo $_GET['prenom'];
        echo $request->query->get('prenom');

        // Notice: Undefined index: surnom
        //echo $_GET['surnom'];

        // pas de notice si la clé n'existe pas dans $_GET
        echo $request->query->get('surnom'); // null

        // valeur par défaut si la clé n'existe pas dans $_GET
        echo '<br>' . $request->query->get('surnom', 'John Doe'); // John Doe

        // isset($_GET['surnom']);
        dump($request->query->has('surnom')); // false

        echo '<br>' . $request->getMethod(); // GET ou POST

        // si la page a été appelée en POST
        if ($request->isMethod('POST')) {
            // $request->request contient un objet qui est une surcouche à $_POST,
            // et qui contient les mêmes méthodes que $request->query
            dump($request->request->all());

            // $_POST['nom']
            echo '<br>' . $request->request->get('nom');
        }

        return $this->render('http/requete.html.twig');
    }

    /**
     * @Route("/reponse")
     *
     */
    public function reponse(Request $request)
    {
        // http://127.0.0.1:8000/http/reponse?type=text
        if ($request->query->get('type') == 'text') {
            // retourne un objet Response qui contient du contenu
            // en texte brut
            $reponse = new Response('Contenu en texte brut');

            return $reponse;
        // http://127.0.0.1:8000/http/reponse?type=json
        } elseif ($request->query->get('type') == 'json') {
            $data = [
                'nom' => 'Marx',
                'prenom' => 'Groucho'
            ];

            // return new Response(json_encode($data));

            // encode le tableau $data en json et retourne
            // un objet Response dont le contenu est le json
            return new JsonResponse($data);
        // http://127.0.0.1:8000/http/reponse?found=no
        } elseif ($request->query->get('found') == 'no') {
            // pour retourner une 404, on jete cette Exception
            throw new NotFoundHttpException();
        // http://127.0.0.1:8000/http/reponse?redirect=hello
        } elseif ($request->query->get('redirect') == 'hello') {
            // redirection vers une page en utilisant le nom de sa route
            return $this->redirectToRoute('app_index_hello');
        // http://127.0.0.1:8000/http/reponse?redirect=bonjour
        } elseif ($request->query->get('redirect') == 'bonjour') {
            // redirection vers une route qui contient une partie variable
            return $this->redirectToRoute(
                'app_routing_bonjour',
                [
                    'qui' => 'le monde'
                ]
            );
        }

        // retourne un objet Response qui contient le HTML
        // produit par le template
        return $this->render('http/reponse.html.twig');
    }

    /**
     * on utilise un paramètre typé SessionInterface
     * pour accéder à la session
     *
     * @Route("/session")
     */
    public function session(SessionInterface $session)
    {
        // pour ajouter des éléments à la session
        $session->set('prenom', 'Julien');
        $session->set('nom', 'Anest');

        // les éléments sont enregistrés dans $_SESSION['_sf2_attributes']
        var_dump($_SESSION);

        // pour accéder directement aux éléments ajoutés :
        var_dump($session->all()); // ['prenom' => 'Julien', 'nom' => 'Anest']

        // pour accéder à un élément de la session
        echo $session->get('prenom');

        echo '<br>' . $session->get('prenom') . ' ' . $session->get('nom');

        // pour supprimer un élément de la session
        $session->remove('nom');

        var_dump($session->all()); // ['prenom' => 'Julien']

        return $this->render('http/session.html.twig');
    }

    /*
     * Faire une page avec un formulaire en post avec :
     * - email (text)
     * - message (textarea)
     *
     * Si le formulaire est envoyé, vérifier que les deux champs sont remplis
     * Si non, afficher un message d'erreur
     * Si oui, enregistrer les valeurs en session
     *
     *
     *
     *
     *
     * ... et rediriger vers une nouvelle page qui les affiche
     * ... et vide la session
     * Dans cette page, si la session est vide, on redirige vers le formulaire
     */

    /**
     *
     * @Route("/formulaire")
     */
    public function formulaire(Request $request, SessionInterface $session)
    {
        $erreur = '';

        // si le formulaire en POST a été envoyé
        if ($request->isMethod('POST')) {
            // les valeurs qui viennent du formulaire
            $email = $request->request->get('email'); // $email = $_POST['email'];
            $message = $request->request->get('message'); // $message = $_POST['message'];

            if (!empty($email) && !empty($message)) {
                // enregistrement en session
                $session->set('email', $email); // $_SESSION['email'] = $email;
                $session->set('message', $message); // $_SESSION['message'] = $message;

                // dump($session->all());

                // redirection vers la page d'affichage
                return $this->redirectToRoute('app_http_affichage');
                // en procédural :
                // header('Location: /http/affichage')
                // die;
            } else {
                $erreur = 'Tous les champs sont obligatoires';
            }
        }

        return $this->render(
            'http/formulaire.html.twig',
            [
                'erreur' => $erreur
            ]
        );
    }

    /**
     * @Route("/affichage")
     */
    public function affichage(SessionInterface $session)
    {
        $email = $session->get('email'); // $email = $_SESSION['email'];
        $message = $session->get('message'); // $message = $_SESSION['message'];

        // si email et message ne sont pas en session
        if (empty($email) && empty($message)) {
            // redirection vers le formulaire
            return $this->redirectToRoute('app_http_formulaire');
        }

        // suppression dans la session
        $session->remove('email'); // unset($_SESSION['email']);
        $session->remove('message'); // unset($_SESSION['message']);

        return $this->render(
            'http/affichage.html.twig',
            [
                'email' => $email,
                'message' => $message
            ]
        );
    }
}
