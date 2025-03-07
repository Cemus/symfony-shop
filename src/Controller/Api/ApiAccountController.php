<?php

namespace App\Controller\Api;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ApiAccountController extends AbstractController
{
    public function __construct(
        private AccountRepository $accountRepository,
        private ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

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

    public function addAccount(Request $request): Response
    {
        $request = $request->getContent();
        $account = $this->serializer->deserialize($request, Account::class, 'json');

        if (empty($account->getFirstname()) || empty($account->getLastname()) || empty($account->getEmail()) || empty($account->getPassword()) || empty($account->getRoles())) {
            $message = "Pas assez d'informations données lors de l'ajout du compte...";
            $code = 400;
        }

        if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
            $message = "Le compte de " . $account->getFirstname() . $account->getLastname() . " a été crée avec succès !!!1!";
            $this->em->persist($account);
            $this->em->flush();
            $code = 201;
        } else {
            $message = "Le compte existe déjà";
            $code = 400;
        }

        $data = $code === 201 ? ["account" => $account, "message" => $message] : ["message" => $message];

        return $this->json($data, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ['groups' => ['account:read']]);
    }

    #[Route(path: '/api/up-account', name: 'api_account_update', methods: ['PUT'])]
    public function updateAccount(Request $request): Response
    {
        $request = $request->getContent();
        $newData = $this->serializer->deserialize($request, Account::class, 'json');

        if (empty($newData->getFirstname()) || empty($newData->getLastname()) || empty($newData->getEmail())) {
            $message = "Pas assez d'informations données lors de l'ajout du compte...";
            $code = 400;
        }

        $prevAccount = $this->accountRepository->findOneBy(["email" => $newData->getEmail()]);
        if (!empty($prevAccount)) {
            $prevAccount->setFirstname($newData->getFirstname())->setLastname($newData->getLastname());
            $message = "Le compte de " . $prevAccount->getFirstname() . $prevAccount->getLastname() . " a été modifié avec succès !!!1!";
            $this->em->flush();
            $code = 200;
        } else {
            $message = "Le compte n'existe pas";
            $code = 400;
        }

        $data = $code === 200 ? ["account" => $prevAccount, "message" => $message] : ["message" => $message];

        return $this->json($data, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ['groups' => ['account:read']]);
    }

    #[Route(path: '/api/delete-account', name: 'api_account_delete', methods: ['DELETE'])]
    public function deleteAccount(Request $request): Response
    {
        $request = $request->getContent();
        $data = json_decode($request, true);
        if (empty($data["id"])) {
            $message = "Besoin d'un id...";
            $code = 400;
        }

        $account = $this->accountRepository->findOneBy(["id" => $data["id"]]);

        if (!empty($account)) {
            $articles = $this->articleRepository->findAll();
            $removedArticles = 0;
            foreach ($articles as $article) {
                if ($article->getAuthor()->getId() == $account->getId()) {
                    $this->em->remove($article);
                    $removedArticles++;
                }
            }
            $message = "Le compte de " . $account->getFirstname() . $account->getLastname() . " a été supprimé avec succès !!!1! | " . $removedArticles . " articles supprimés durant l'opération.";
            $this->em->remove($account);
            $this->em->flush();
            $code = 200;
        } else {
            $message = "Le compte n'existe pas";
            $code = 400;
        }

        $data = $code === 200 ? ["account" => $account, "message" => $message] : ["message" => $message];

        return $this->json($data, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ['groups' => ['account:read']]);
    }

    #[Route(path: '/api/pswd-account', name: 'api_account_pswd', methods: ['PATCH'])]
    public function changePassword(Request $request): Response
    {
        $request = $request->getContent();
        $newData = $this->serializer->deserialize($request, Account::class, 'json');

        if (empty($newData->getPassword()) || empty($newData->getEmail())) {
            $message = "Besoin d'un password et d'une mail...";
            $code = 400;
        }

        $account = $this->accountRepository->findOneBy(["email" => $newData->getEmail()]);

        if (!empty($account)) {

            $newPassword = $newData->getPassword();
            $hashedPassword = $this->passwordHasher->hashPassword($account, $newPassword);
            $account->setPassword($hashedPassword);

            $this->em->flush();

            $message = "Le compte de " . $account->getFirstname() . $account->getLastname() . " a été modifié avec succès !!!1!";
            $code = 202;
        } else {
            $message = "Le compte n'existe pas";
            $code = 400;
        }

        $data = $code === 200 ? ["account" => $account, "message" => $message] : ["message" => $message];

        return $this->json($data, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], ['groups' => ['account:read']]);
    }

}