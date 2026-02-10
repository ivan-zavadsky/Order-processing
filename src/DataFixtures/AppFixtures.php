<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setName(ucfirst($faker->word));
            $product->setPrice($faker->numberBetween(100, 10000)/100);
            $product->setSku($faker->postcode);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
