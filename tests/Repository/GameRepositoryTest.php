<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameRepositoryTest extends KernelTestCase
{

    public function test_save_should_persist_game(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $game = new Game();
        $game->setName('Assassin\'s Creed Valhalla')
            ->setDescription('Viking action game')
            ->setScore(8)
            ->setReleaseDate(new \DateTime('2020-11-10'));

        $entityManager->getRepository(Game::class)->save($game, true);

        $this->assertNotNull($game->getId());
    }

    public function test_save_should_persist_all_game_data(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $game = new Game();
        $game->setName('Assassin\'s Creed Valhalla')
            ->setDescription('Viking action game')
            ->setScore(8)
            ->setReleaseDate(new \DateTime('2020-11-10'));

        $entityManager->getRepository(Game::class)->save($game, true);
        $game = $entityManager->getRepository(Game::class)->find($game->getId());

        $this->assertEquals('Assassin\'s Creed Valhalla', $game->getName());
        $this->assertEquals('Viking action game', $game->getDescription());
        $this->assertEquals(8, $game->getScore());
        $this->assertEquals(new \DateTime('2020-11-10'), $game->getReleaseDate());
    }
}
