<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User('alex1234567', 'alex@gmail.com', 'some notes');
        $user->setCreated(new \DateTimeImmutable());
        $manager->persist($user);

        $user2 = new User('sample1234567', 'sample@gmail.com');
        $user2->setCreated(new \DateTimeImmutable());
        $manager->persist($user2);
        $manager->flush();
    }
}
