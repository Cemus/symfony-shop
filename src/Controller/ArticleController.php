<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleController,
        private readonly EntityManagerInterface $em
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

    #[Route('/add-article', name: 'app_add_article')]
    public function addArticle(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article );
        $form->handleRequest($request);
        $msg = "";
        $status= "";

        if($form->isSubmitted()){
            try {
                $this->em->persist($article);
                $this->em->flush();
                $msg = "L'article a été ajouté avec succès";
                $status = "success";
            } catch (\Exception $e) {
                $msg ="L'article existe déja";
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('addArticle.html.twig',
        [
            'form'=> $form // ->createView() ; optionnel
        ]);
    }
}