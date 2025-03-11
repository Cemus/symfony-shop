<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;


class ArticleService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ArticleRepository $articleRepository
    ) {
    }
}