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
    
}

