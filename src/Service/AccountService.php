<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account;
use App\Repository\AccountRepository;

class AccountService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AccountRepository $accountRepository
    ) {
    }


    public function save(Account $account): void
    {
        if (
            $account->getFirstname() != "" && $account->getLastname() != "" && $account->getEmail() != "" &&
            $account->getPassword() != ""
        ) {
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                $account->setRoles("ROLE_USER");
                $this->em->persist($account);
                $this->em->flush();
            } else {
                throw new \Exception("Le compte existe déja");
            }
        } else {
            throw new \Exception("Les champs ne sont pas tous remplis");
        }
    }

    public function getAll(): array
    {
        $accounts = [];

        $accounts = $this->accountRepository->findAll();
        if ($accounts == []) {
            throw new \Exception("Aucun compte trouvé", 204);
        }

        return $accounts;
    }

    public function getById($id): Account|null
    {
        $account = $this->accountRepository->find($id);
        if ($account == "") {
            throw new \Exception("Aucun compte trouvé", 204);
        }

        return $account;
    }
}