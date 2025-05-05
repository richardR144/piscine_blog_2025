<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    #[Route('/create-article', name: 'create-article')]
    public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        if($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $description = $request->request->get('description');
            $image = $request->files->get('image');

            
            $article = new Article($title, $description, $content, $image);
            
            //méthode 1: en déclarant tous les setters
            //$article->setTitle($title);
            //$article->setContent($content);
            //$article->setDescription($description);

            //$article->setImage($image);
            //$article->setCreatedAt(new \DateTime());
            
            //méthode 2: en déclarant le constructeur dans l'entité Article
            // $article = new Article($title, $description, $content, $image); et faire le setter dans l'entité Article.php

            $entityManager->persist($article);  //Préparation de l'entité Article pour l'insertion dans la base de données
            $entityManager->flush();            //Envoi de la requête à la base de données pour l'insertion de l'article
        }
        return $this->render('create-article.html.twig');
    }


    #[Route('/list-articles', name: 'list-articles')]  //Route vers la page d'affichage des articles
    public function displayArticle(ArticleRepository $articleRepository): Response  //Injection de dépendance de l'ArticleRepository
    {
     $articles = $articleRepository->findAll();  //Récupération de tous les articles de la base de données avec la méthode findAll()
        return $this->render('list-articles.html.twig', [  //Rendu de la vue list-articles.html.twig
            'articles' => $articles,     //Passage de la variable $articles à la vue
        ]);
     }
}        
    
        dd($articles);
   