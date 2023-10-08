<?php

namespace App\Controller;

use App\Service\CodeGenerator;
use App\Service\GameRankGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends  AbstractController
{
    #[Route('/', name: 'index.home')]
    public function home(): Response
    {
//        dd($this->getParameter('templates_dir'));
        return $this->render('index/home.html.twig');
    }

    #[Route('/about', name: 'index.about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig');
    }

    #[Route('/code', name: 'index.code')]
    public function code(CodeGenerator $codeGenerator): Response
    {
        $code = $codeGenerator->generate();

        return $this->render('index/code.html.twig', ['code' => $code]);
    }

    #[Route('/rank', name: 'index.rank')]
    public function rank(GameRankGenerator $gameRankGenerator): Response
    {
        $gameRank = $gameRankGenerator->generate();

        return $this->render('index/rank.html.twig', ['gameRank' => $gameRank]);
    }

    #[Route('/hello/{firstName}', name: 'index.hello', methods: ['GET'])]
    public function hello(string $firstName = 'Guest'): Response
    {
        $favouriteGames = [
            'Witcher 3',
            'Skyrim'
        ];

        return $this->render('index/hello.html.twig', [
            'firstName' => $firstName,
            'favouriteGames' => $favouriteGames
        ]);
    }

    #[Route('/top', name: 'index.top')]
    public function top()
    {
        $topGames = [
            'Witcher 3',
            'Skyrim'
        ];

        return new JsonResponse($topGames);
    }

    #[Route('/top-game', name: 'index.top-game')]
    public function topGame(): Response
    {
        $topGames = [
            'Witcher 3',
            'Skyrim'
        ];

        return $this->render('index/top-game.html.twig', [
            'topGames' => $topGames
        ]);
    }
}