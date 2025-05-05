<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;


class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function displayHome(): Response
    {
         return $this->render('home.html.twig');
    } 
    
    #[Route('/404', name: '404')] //je créais lea route pour la page 404
    public function display404(): Response  //je créais la méthode pour la page 404
    {
        $html = $this->renderView('404.html.twig'); //je créais la variable html pour le rendu de la page 404
        return new Response($html, 404); //je renvoie la réponse avec le code d'erreur 404
    }
}

//{#Créez une nouvelle page, dans votre classe PageController, nommée 404
//Créez un fichier twig 404.html.twig affichant "page non trouvée"
//Dans la fonction de controleur, générez le html issu du fichier twig 404 en utilisant la fonction $this->renderView
//Retournez une réponse HTTP incluant le HTML généré et un status 200#}

