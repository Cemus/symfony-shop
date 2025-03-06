<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    #[Route('/api/articles', name: 'api_article_all')]
    public function getAllArticle(): Response
    {
        return $this->json(
            $this->articleRepository->findAll(),
            200,
            [],
            ['groups' => 'article:read']
        );
    }
    #[Route('/api/articles/{id}', name: 'api_article_one')]

    public function getArticleById($id): Response
    {
        return $this->json(
            $this->articleRepository->findOneBy(['id' => $id]),
            200,
            [],
            ['groups' => ['article:read', 'article:id']]
        );
    }
}