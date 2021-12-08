<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $articleRepository, Request $request)
    {
        return $this->render('home/index.html.twig', [
                'mostLikedArticles' => $articleRepository->findMostLiked(5),
                'mostRecentArticles' => $articleRepository->findMostRecent(5)
        ]);
    }
}
