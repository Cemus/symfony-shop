<?php

namespace App\Service;


use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly AccountRepository $accountRepository)
    {
    }

    public function save(Account $account)
    {
        if ($account->getFirstname() != "" && $account->getLastname() != "" && $account->getEmail() != "" && $account->getPassword() !== "") {
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {

            }

        }
    }
}
