<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $articleRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $articles = $articleRepository->findAll();
        $page = $paginator->paginate(
            $articles, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $page
        ]);
    }

    /**
     * @Route("/article/new", methods={"GET","POST"}, name="article.new")
     */
    public function create(Request $request,ManagerRegistry $doctrine): Response{
        
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);


        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $article->setCreatedAt(new \DateTime);
            $entityManager = $doctrine->getManager();
             // tell Doctrine you want to (eventually) save the Product (no queries yet)
             $entityManager->persist($article);

             // actually executes the queries (i.e. the INSERT query)
             $entityManager->flush();
            
            return $this->redirectToRoute('article.show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/create.html.twig', [
            'articleForm' => $articleForm->createView() 
        ]);
    }

    /**
     * @Route("/article/{id}/edit", methods={"GET","POST"}, name="article.edit")
     */
    public function update(int $id,ArticleRepository $articleRepository,Request $request,ManagerRegistry $doctrine): Response{
        
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);


        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $entityManager = $doctrine->getManager();
             // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($article);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();
            return $this->redirectToRoute('article.show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/update.html.twig', [
            'articleForm' => $articleForm->createView() 
        ]);
    }

    /**
     * @Route("/article/{id}/delete", name="article.delete")
     */
    public function delete(int $id,ArticleRepository $articleRepository,Request $request,ManagerRegistry $doctrine): Response{
        
        $article = $articleRepository->find($id);

        if(!$article)
        {
            throw $this->createNotFoundException('No ID found');
        }

        $csrfToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-item', $csrfToken))
        {
            $entityManager = $doctrine->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->remove($article);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            $this->addFlash('success', "L'article {$article->getTitle()} a bien été supprimé !");
        } else {
            throw new InvalidCsrfTokenException();
        }
        
        return $this->redirectToRoute('article');
    }

    /**
     * @Route("/article/{id}/unlike", name="article.unlike")
     */
    public function unlike(int $id,ArticleRepository $articleRepository,Request $request,ManagerRegistry $doctrine): Response{
        $article = $articleRepository->find($id);
        if (!$article)
        {
            return $this->json('The article does not exist', 404);
        }
        $entityManager = $doctrine->getManager();
        $article->decrementLikes();
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->json(['likes' => $article->getLikes()], 200);
    }

    /**
     * @Route("/article/{id}/like", name="article.like")
     */
    public function like(int $id,ArticleRepository $articleRepository,Request $request,ManagerRegistry $doctrine): Response{
        $article = $articleRepository->find($id);
        if (!$article)
        {
            return $this->json('The article does not exist', 404);
        }
        $entityManager = $doctrine->getManager();
        $article->incrementLikes();
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->json(['likes' => $article->getLikes()], 200);
    }

    /**
     * @Route("/article/{id}", methods={"GET"}, name="article.show")
     */
    public function show(int $id,ArticleRepository $articleRepository): Response{
        $article = $articleRepository->find($id);
        if (!$article)
        {
            throw $this->createNotFoundException('The article does not exist');
        }
        return $this->render('article/show.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article
        ]);

        
    }

    
}
