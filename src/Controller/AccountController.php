<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly UserPasswordHasherInterface $passwordHasher

    ) {}

    #[Route('/accounts', name: 'app_accounts')]
    public function showAllCategories(): Response
    {

        return $this->render('accounts.html.twig', [
            'accounts' => $this->accountRepository->findAll(),
        ]);
    }

    #[Route('/add-account', name: 'app_add_account')]
    public function addAccount(Request $request): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class,$account );
        $form->handleRequest($request);
        $msg = "";
        $status= "";

        if($form->isSubmitted()){
            try {
                $newPassword = $account->getPassword();
                $hashedPassword = $this->passwordHasher->hashPassword($account, $newPassword);
                $account->setPassword($hashedPassword);

                $this->em->persist($account);
                $this->em->flush();
        
                $msg = "Le compte a été ajouté avec succès";
                $status = "success";
            } catch (\Exception $e) {
                $msg ="Le compte existe déja";
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('addAccount.html.twig',
        [
            'form'=> $form // ->createView() ; optionnel
        ]);
    }
}