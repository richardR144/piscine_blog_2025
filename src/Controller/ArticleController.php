<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
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

            $entityManager->persist($article);
            $entityManager->flush();
        }
        return $this->render('create-article.html.twig');
    }
}
        
    
