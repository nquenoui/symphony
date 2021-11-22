<?php

namespace App\Controller;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

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
