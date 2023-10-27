<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Game extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $game = new \App\Entity\Game();
            $game->setName('Game ' . $i)
                ->setDescription('Description ' . $i)
                ->setScore($i + 1)
                ->setReleaseDate(new \DateTime('2020-11-10'));
            $manager->persist($game);
        }

        $manager->flush();
    }
}
