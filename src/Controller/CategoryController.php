<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoryRepository;

final class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {}

    #[Route('/category', name: 'app_category')]
    public function showAllCategories(): Response
    {

        return $this->render('categories.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/add-category', name: 'app_add-category')]
    public function addCategory(): Response
    {

        return $this->render('add-categories.html.twig');
    }
}