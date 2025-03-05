<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository
    ) {}

    #[Route('/accounts', name: 'app_accounts')]
    public function showAllCategories(): Response
    {

        return $this->render('accounts.html.twig', [
            'accounts' => $this->accountRepository->findAll(),
        ]);
    }
}