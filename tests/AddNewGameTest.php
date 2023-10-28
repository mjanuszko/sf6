<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddNewGameTest extends WebTestCase
{
    public function testNewGameFormRenders(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('michal@januszko.net');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/game/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Add new game');
    }

    public function testFormSubmitAddsNewGame(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('michal@januszko.net');
        $client->loginUser($testUser);
        $client->request('GET', '/game/new');

        $client->submitForm('Save', [
            'game[name]' => 'Test game',
            'game[description]' => 'Test description',
            'game[releaseDate]' => '2020-01-01',
            'game[score]' => 10,
        ]);
        $client->followRedirect();

        // assert that we are on the game page
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Test game');
    }
}
