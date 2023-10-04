<?php

namespace App\Service;

class GameRankGenerator
{
    private array $listOfGames;

    /**
     * GameRankGenerator constructor.
     * @param array $listOfGames
     */
    public function __construct(array $listOfGames)
    {
        $this->listOfGames = $listOfGames;
    }
    public function generate(): array
    {
        $rank = [];
        while (count($rank) < 5) {
            $game = $this->listOfGames[rand(0, count($this->listOfGames) - 1)];

            if (!in_array($game, $rank)) {
                $rank[] = $game;
            }
        }
        return $rank;
    }
}