<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
{
    $faker = Faker\Factory::create();

    for ($i = 1; $i <= 10; $i++)
    {
        $categorie = new Categorie();

        $categorie->setName($faker->name())
                  ->setDescription($faker->text(1500));

        $manager->persist($categorie);
    }

    $manager->flush();
}



}
