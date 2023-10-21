<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/games', name: 'app_game_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $this->DenyAccessUnlessGranted('ROLE_USER');

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

    #[Route('/game/new', name: 'app_game_new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->DenyAccessUnlessGranted('ROLE_ADD');

        $game = new Game();
        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();
            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Game created!');

            return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/game/edit/{id}', name: 'app_game_edit')]
    public function edit(Game $game, EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->DenyAccessUnlessGranted('ROLE_EDIT');

        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();
            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Game updated!');

            return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
        }

        return $this->render('game/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/game/{id}', name: 'app_game_show')]
    public function show(Game $game): Response
    {
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
