<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleController
    ) {}
    #[Route(path: '/articles',name:'app_articles')]
    public function showAllArticles(): Response
    {
        return $this->render('articles.html.twig', [
            'articles' => $this->articleController->findAll(),
        ]);
    }
    #[Route(path: '/detail',name:'app_detail')]
    public function articleId(): Response
    {
        return $this->render('detail.html.twig', []);
    }
}