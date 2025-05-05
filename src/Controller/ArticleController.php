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

//David: Après la création de l'instance de la classe Article, utilisez une instance la 
//classe EntityManagerInterface pour insérer en bdd les données de l'article

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


    //David: Créez une nouvelle page avec l'url "list-articles", qui affichent tous les articles de la table article

    #[Route('/list-articles', name: 'list-articles')]  //Route vers la page d'affichage des articles
    public function displayArticle(ArticleRepository $articleRepository): Response  //Injection de dépendance de l'ArticleRepository
    {
     $articles = $articleRepository->findAll();  //Récupération de tous les articles de la base de données avec la méthode findAll()
        return $this->render('list-articles.html.twig', [  //Rendu de la vue list-articles.html.twig
            'articles' => $articles,     //Passage de la variable $articles à la vue
        ]);
     }




     // David: Créez une nouvelle page avec l'url "details-article/{id}", qui affichent les détails d'un article de la table article
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
}        
    
//{#Créez une nouvelle page, dans votre classe PageController, nommée 404
//Créez un fichier twig 404.html.twig affichant "page non trouvée" 
//Si dans le cas où l'article n'existe pas, redirigez vers la page 404
//Dans la fonction de controleur, générez le html issu du fichier twig 404 en utilisant la fonction $this->renderView
//Retournez une réponse HTTP incluant le HTML généré et un status 200#}       
