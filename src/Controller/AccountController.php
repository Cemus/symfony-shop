<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Service\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AccountService $accountService

    ) {
    }

    #[Route('/accounts', name: 'app_accounts')]
    public function showAllAccounts(): Response
    {
        $errorMsg = "";
        $accounts = [];
        try {
            $accounts = $this->accountService->getAll();
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
        return $this->render('accounts.html.twig', [
            'accounts' => $accounts,
            'errorMsg' => $errorMsg ?? null
        ]);
    }

    #[Route('/account-id/{id}', name: 'app_account_id')]
    public function showById(int $id): Response
    {
        $errorMsg = "";

        try {
            $account = $this->accountService->getById($id);
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }

        return $this->render('accountId.html.twig', [
            'account' => $account ?? null,
            'errorMsg' => $errorMsg ?? null
        ]);
    }

    #[Route('/add-account', name: 'app_add_account')]
    public function addAccount(Request $request): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = "";
        $status = "";

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->accountService->save($account);
                $msg = "Le compte a été ajouté avec succès";
                $status = "success";
            } catch (Exception $e) {
                $msg = "Le compte existe déja";
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render(
            'addAccount.html.twig',
            [
                'form' => $form // ->createView() ; optionnel
            ]
        );
    }
}