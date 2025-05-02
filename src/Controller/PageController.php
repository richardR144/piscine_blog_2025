<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function displayHome(): Response
    {
        $article = new Article();
        $article->setTitle('')
            ->setDescription('')
            ->setContent('This is the content of my first article')
            ->setImage('image.jpg')
            ->setCreatedAt(new \DateTime())
            ->setIsPublished(true);

            return $this->render('home/index.html.twig', [
                'article' => $article,
            ]);

                //return new Response('Article created with id ' . $article->getId());
    }
}
{
   
    
}

//dd('hello');