<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = json_decode(
            file_get_contents(__DIR__.'/data/users.json'),
            true
        );

        foreach ($data as $item) {
            $user = new User();
            $user->setName($item['name']);
            $manager->persist($user);
        }

        $manager->flush();

    }
}
