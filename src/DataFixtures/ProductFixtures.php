<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = json_decode(
            file_get_contents(__DIR__.'/data/products.json'),
            true
        );

        foreach ($data as $item) {
            $product = new Product();
            $product->setName($item['name']);
            $product->setPrice($item['price']);
            $product->setSku($item['sku']);
            $manager->persist($product);
        }

        $manager->flush();

    }
}
