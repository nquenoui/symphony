<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
{
    $faker = Faker\Factory::create();
    $categories = $manager->getRepository(Categorie::class)->findAll();

    for ($i = 1; $i <= 10; $i++)
    {
        $article = new Article();

        $sentence = $faker->sentence(4);
        $title = substr($sentence, 0, strlen($sentence) - 1);
        $index = rand(0, count($categories) - 1);
        $category = $categories[$index];

        $article->setTitle($title)
                ->setAuthor($faker->name())
                ->setContent($faker->text(1500))
                ->setCreatedAt($faker->dateTimeThisYear())
                ->setCategory($category);

        $manager->persist($article);
    }

    $manager->flush();
}
}
