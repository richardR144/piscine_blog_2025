<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'list-category')]
    public function displayListCategory(CategoryRepository $categoryRepository): Response
    {
        
        $categories = $categoryRepository->findAll();

        return $this->render('list-category.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    #[Route('/categorie/{id}', name: 'show-category')]  
    public function showCategory(CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->redirectToRoute('404');
        }
    
        return $this->render('show-category.html.twig', [
            'category' => $category
        ]);
    }
}
    
