<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


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
    public function displayShowCategory($id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            return $this->redirectToRoute('404');
        }
    
        return $this->render('details-category.html.twig', [
            'category' => $category
        ]);
    }

    //David : générez un gabarit de formulaire pour la création de category avec "php bin/console make:form" (répondez Category aux deux questions)
    //créez une page sur l'url create-category, qui instancie la classe Category et utilise le gabarit de formulaire pour afficher le formulaire dans un fichier twig
    //Dans le controleur, récupèrez les données envoyées en POST et stockez les en bdd grâce au système de formulaire de symfony

    //Création d'une catégorie
    #[Route('/create-category', name: 'create-category')]
    public function displayCreateCategory(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryForm::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted()) {

            $category->setCreatedAt(new \DateTime());
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie a été créée avec succès !');
        }

        return $this->render('create-category.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    } 
    
    //Modification d'une catégorie
    #[Route('/update-category/{id}', name: 'update-category')]
    public function displayupdateCategory($id, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        
        $category = $categoryRepository->find($id);
        $categoryForm = $this->createForm(CategoryForm::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted()) {

            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'La catégorie a été créée avec succès !');
        }

        return $this->render('update-category.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }

}   

