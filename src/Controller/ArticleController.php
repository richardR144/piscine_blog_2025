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
use Doctrine\ORM\EntityRepository; //Importation de la classe EntityRepository pour la gestion des entités
use App\Repository\CategoryRepository; //Importation de la classe CategoryRepository pour la gestion des catégories

// Création d'articles
//David: Après la création de l'instance de la classe Article, utilisez une instance la 
//classe EntityManagerInterface pour insérer en bdd les données de l'article

class ArticleController extends AbstractController
{
    #[Route('/create-article', name: 'create-article')]
    public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        if($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $description = $request->request->get('description');
            $image = $request->request->get('image');
            

            
            $article = new Article($title, $description, $content, $image); //Création d'une nouvelle instance de l'entité Article avec les données du formulaire
            
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
            $message = $this->addFlash('success', 'L\'article a été créé avec succès !'); //Création d'un message flash de succès pour la création de l'article
        }
        return $this->render('create-article.html.twig');
    }

    //Liste d'articles
    //David: Créez une nouvelle page avec l'url "list-articles", qui affichent tous les articles de la table article

    #[Route('/list-articles', name: 'list-articles')]  //Route vers la page d'affichage des articles
    public function displayArticle(ArticleRepository $articleRepository): Response  //Injection de dépendance de l'ArticleRepository
    {
     $articles = $articleRepository->findAll();  //Récupération de tous les articles de la base de données avec la méthode findAll()
        return $this->render('list-articles.html.twig', [  //Rendu de la vue list-articles.html.twig
            'articles' => $articles,     //Passage de la variable $articles à la vue
        ]);
     }



     //Détails de l'article
     // David: 1 Créez une nouvelle page avec l'url "details-article/{id}", qui affichent les détails d'un article de la table article
     // Ne pas oublier de mettre dans l'url l'id de l'article à afficher, ex: details-article/1
     #[Route('/details-article/{id}', name: 'details-article')]  //Route vers la page d'affichage des détails d'un article
     public function displayArticleDetails($id, ArticleRepository $articleRepository) //Injection de dépendance de l'ArticleRepository
     {
        //SELECT * FROM article WHERE id = $id
       $article = $articleRepository->find($id);  //Récupération de l'article avec l'id passé en paramètre de la route
       if (!$article) {  //Si l'article n'existe pas
           return $this->redirectToRoute('404');  
       } 
        //SELECT * FROM article WHERE id = $id
       return $this->render('details-article.html.twig', [  //Rendu de la vue details-article.html.twig
           'article' => $article,     //Passage de la variable $article à la vue
       ]);
     }

     // Supprimer l'article (5)
     public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager) //Injection de dépendance de l'ArticleRepository
     {
        //SELECT * FROM article WHERE id = $id
        $article = $articleRepository->find($id);  //Récupération de l'article avec l'id passé en paramètre de la route
        $entityManager->remove($article); //Préparation de l'entité Article pour la suppression dans la base de données
        $entityManager->flush();            //Envoi de la requête à la base de données pour la suppression de l'article

        $message = $this->addFlash('success', 'L\'article a été supprimé avec succès !'); //Création d'un message flash de succès pour la suppression de l'article

        return $this->redirectToRoute('list-articles'); //Redirection vers la liste des articles après la suppression   

       }


        // David (6) : Créez une nouvelle page pour mettre à jour (update/Modifier) un article
        //Dans l'url, ajoutez un parametre id
        //Récupérez en bdd l'article lié à cet id
        //Affichez un formulaire en twig avec en champs le titre, image, content et description, chacun pré-rempli avec les valeurs de l'article récupér

       #[Route('/update-article/{id}', name: 'update-article')]  //Route vers la page de mise à jour d'un article
       public function displayUpdateArticle($id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager) //Injection de dépendance de l'ArticleRepository
       {
        
        //SELECT * FROM article WHERE id = $id
        $article = $articleRepository->find($id);  //Récupération de l'article avec l'id passé en paramètre de la route
            
        if ($request->isMethod("POST")) {  //Si l'article n'existe pas
                $title = $request->request->get('title');  //Récupération du titre du formulaire
                $content = $request->request->get('content');  //Récupération du contenu du formulaire
                $description = $request->request->get('description');  //Récupération de la description du formulaire
                $image = $request->request->get('image');  //Récupération de l'image du formulaire

                //Méthode 1: en déclarant tous les setters
                //$article->setTitle($title);
                //$article->setContent($content);
                //$article->setDescription($description);
                //$article->setImage($image);
                //$article->setCreatedAt(new \DateTime());

                //Méthode 2: Mise à jour de l'article dans l'entité Article (avec la méthode updat et respecte l'encapsulation)
                // $article = new Article($title, $description, $content, $image); et faire le setter dans l'entité Article.php

                $article->update($title, $description, $content, $image); //Mise à jour de l'article avec les nouvelles valeurs du formulaire

                $entityManager->persist($article);  //Préparation de l'entité Article pour la mise à jour dans la base de données
                $entityManager->flush();            //Envoi de la requête à la base de données pour la mise à jour de l'article
                
                $message = $this->addFlash('success', 'L\'article a été modifié avec succès !');
            }
            
            return $this->render('update-article.html.twig', [  //Rendu de la vue details-article.html.twig
            'article' => $article     //Passage de la variable $article à la vue
        ]);
}
        
}
   
       
   
//2 {#Créez une nouvelle page, dans votre classe PageController, nommée 404
//Créez un fichier twig 404.html.twig affichant "page non trouvée" 
//Si dans le cas où l'article n'existe pas, redirigez vers la page 404
//Dans la fonction de controleur, générez le html issu du fichier twig 404 en utilisant la fonction $this->renderView
//Retournez une réponse HTTP incluant le HTML généré et un status 200#} 

//3 Créez une nouvelle page, dans votre classe ArticleController, nommée delete-articles
//4 Créez un base.html.twig avec un header contenant menu avec des liens vers la page d'accueil 
//  et la page de liste des articles, Faites un peu de CSS,
//  Dans la liste des articles, pour chaque article,  n'affichez que le titre et l'image et 
//  faites un lien vers la page de détails de l'article pour voir le reste

//5 Créez un bouton supprimer sur la page de détails de l'article qui appelle la route delete-article/{id} par l'id de l'article
// 5.1 Dans la méthode deleteArticle, utilisez l'EntityManagerInterface pour supprimer l'article de la base de données
// 5.2 Dans la méthode deleteArticle, utilisez l'EntityManagerInterface pour supprimer l'article de la base de données
// 5.3 David: Créez une page dans le controller d'article, qui permet de supprimer un article en fonction de son id
//Cette méthode prend un id en parametre d'url, récupère l'article avec le repository et le supprime avec l'entity manager
//après la supression, ajoutez un message flash de succès et redirigez vers la page de liste des articles
//dans la liste des articles, faites un lien pour chaque article vers la page de suppression de de l'article
//pensez à ajoutez l'affichage des messages flashes dans votre votre base.html.twig
//6 Créez un message flash de succès pour la suppression de l'article et redirigez vers la page d'accueil
