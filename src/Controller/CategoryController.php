<?php

namespace App\Controller;

use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;

final class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/category', name: 'app_category')]
    public function showAllCategories(): Response
    {

        return $this->render('categories.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/add-category', name: 'app_add_category')]
    public function addCategory(): Response
    {
        $form = $this->createForm(CategoryType::class);

        return $this->render('addCategory.html.twig',[
            'form' => $form // ->createView() ; facultatif
        ]);
    }
}