<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        }

        // retourne un objet Response qui contient le HTML
        // produit par le template
        return $this->render('http/reponse.html.twig');
    }
}
