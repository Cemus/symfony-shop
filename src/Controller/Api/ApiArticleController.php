<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Repository\AccountRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isNull;

class ApiArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private AccountRepository $accountRepository,
        private CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ) {
    }

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

    #[Route(path: '/api/add-article', name: 'api_article_add', methods: ['POST'])]
    public function addArticle(Request $request): Response
    {
        $request = $request->getContent();
        $article = $this->serializer->deserialize($request, Article::class, 'json');
        if (empty($article->getTitle()) || empty($article->getContent()) || empty($article->getAuthor()) || empty($article->getCategories())) {
            $message = "L'article a besoin d'avoir du contenu, d'un titre, d'un auteur et des catégories !";
            $code = 400;
        } else {
            foreach ($article->getCategories() as $key => $value) {
                $category = $value->getName();
                $article->removeCategory($value);
                $category = $this->categoryRepository->findOneBy(["name" => $category]);
                $article->addCategory($category);
            }

            $author = $this->accountRepository->findOneBy(["email" => $article->getAuthor()->getEmail()]);

            if (!empty($author)) {
                $article->setAuthor($author);
                if (!$this->articleRepository->findOneBy(["title" => $article->getTitle()])) {
                    $article->setCreateAt(new DateTime("now"));
                    $this->em->persist($article);
                    $this->em->flush();
                    $message = "Article ajouté avec succès !";
                    $code = 201;
                } else {
                    $code = 400;
                    $message = "L'article existe déjà";
                }
            } else {
                $code = 400;
                $message = "Auteur inconnu.";
            }

        }
        return $this->json(
            ["article" => $article, "message" => $message],
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'articles:read']
        );
    }
}