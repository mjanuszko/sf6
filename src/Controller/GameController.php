<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/games', name: 'app_game_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $games = $entityManager->getRepository(Game::class)->findAll();

        return $this->render('game/list.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/games/score/{score}', name: 'app_game_score_list')]
    public function score(EntityManagerInterface $entityManager, int $score): Response
    {
        $games = $entityManager->getRepository(Game::class)->findAllWithScoreDQL($score);

        return $this->render('game/list.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/game', name: 'app_game')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
//        $game->setName('The Legend Of Zelda: Breath Of The Wild')
//            ->setDescription('Link saves the princess')
//            ->setScore(10)
//            ->setReleaseDate(new \DateTime('2017-03-03'));
        $game->setName('Super Mario Odyssey')
            ->setDescription('Mario saves the princess')
            ->setScore(9)
            ->setReleaseDate(new \DateTime('2017-10-27'));

        $entityManager->persist($game);
        $entityManager->flush();

        return new Response('Saved new game with id: ' . $game->getId());
    }

    #[Route('/game/{id}', name: 'app_game_show')]
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/game/{id}/edit', name: 'app_game_edit')]
    public function edit(EntityManagerInterface $entityManager, int $id): Response
    {
        $game = $entityManager->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }

        $game->setScore(9);

        $entityManager->flush();

        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/game/{id}/delete', name: 'app_game_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $game = $entityManager->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id ' . $id
            );
        }

        $entityManager->remove($game);
        $entityManager->flush();

        return new Response('Deleted game with id: ' . $id);
    }
}
