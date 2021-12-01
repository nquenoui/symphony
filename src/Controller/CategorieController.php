<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(CategorieRepository $categorieRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $categorie = $categorieRepository->findAll();
        $page = $paginator->paginate(
            $categorie, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
            'categories' => $page
        ]);
    }

    /**
     * @Route("/categorie/{id}", methods={"GET"}, name="categorie.show")
     */
    public function show(int $id,CategorieRepository $categorieRepository,ArticleRepository $articleRepository): Response{
        
        $categorie = $categorieRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('The category does not exist');
        }

        $articles = $articleRepository->findBy(['category' => $id]);

        return $this->render('categorie/show.html.twig', [
            'controller_name' => 'CategorieController',
            'categorie' => $categorie,
            'articles' => $articles
        ]);
    }
}
