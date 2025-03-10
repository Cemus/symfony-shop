<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function addCategory(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category );
        $form->handleRequest($request);
        $msg = "";
        $status= "";

        if($form->isSubmitted()){
            try {
                $this->em->persist($category);
                $this->em->flush();
                $msg = "La catégorie a été ajoutée avec succès";
                $status = "success";
            } catch (\Exception $e) {
                $msg ="La catégorie existe déja";
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('addCategory.html.twig',
        [
            'form'=> $form // ->createView() ; optionnel
        ]);
    }
}