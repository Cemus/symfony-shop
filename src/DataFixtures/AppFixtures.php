<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $accounts = [];

        for ($i=0; $i < 50; $i++) { 
            $account = new Account();

            $account->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setEmail($faker->email())
            ->setPassword($faker->password())
            ->setRoles("ROLE_USER");

            array_push($accounts, $account);

            $manager->persist($account);
        }

        for ($i=0; $i < 100; $i++) { 
            $article = new Article();
            $article->setAuthor($accounts[$faker->numberBetween(0,49)])
            ->setContent($faker->realText())
            ->setCreateAt($faker->dateTime())
            ->setTitle($faker->title());
    
            $manager->persist($article);
        }

        $manager->flush();
    }

}
