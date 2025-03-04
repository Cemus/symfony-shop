<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route(path: '/articles',name:'app_articles')]
    public function allArticles(): Response
    {
        return $this->render('articles.html.twig', []);
    }
    #[Route(path: '/detail',name:'app_detail')]
    public function articleId(): Response
    {
        return $this->render('detail.html.twig', []);
    }
}