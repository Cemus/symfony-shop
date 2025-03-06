<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ApiAccountController extends AbstractController
{
    public function __construct(
        private AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ) {}

    #[Route(path: '/api/accounts', name: 'api_account_all')]
    public function getAllAccount(): Response
    {
        return $this->json(
            $this->accountRepository->findAll(),
            200,
            [],
            ['groups' => 'account:read']
        );
    }

    #[Route('/api/accounts/{id}', name: 'api_account_one')]

    public function getAccountById($id): Response
    {
        return $this->json(
            $this->accountRepository->findOneBy(['id' => $id]),
            200,
            [],
            ['groups' => ['account:read']]
        );
    }
    
    #[Route(path: '/api/add-account', name: 'api_account_add', methods: ['POST'])]

    public function addAccount(Request $request):Response
    {
        $request = $request->getContent();
        $account = $this->serializer->deserialize($request, Account::class, 'json');

        if ( empty($account->getFirstname()) || empty($account->getLastname()) || empty($account->getEmail()) || empty($account->getPassword()) || empty($account->getRoles())) {
            $account = "Pas assez d'informations données lors de l'ajout du compte...";
            $code = 400;
        }

        if (!$this->accountRepository->findOneBy(
            ["email" => $account->getEmail()])) {
            $this->em->persist($account);
            $this->em->flush();
            $code = 201;
        } 
        else  {
            $account = "Le compte existe déjà";
            $code = 400;
        }

        return $this->json($account, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }
}