<?php

namespace App\Controller;

use App\DTO\CommentRequestDto;
use App\DTO\CreateArticleRequestDto;
use App\DTO\UpdateArticleRequestDto;
use App\Entity\Comment;
use App\Entity\NewsArticle;
use App\Form\NewsArticleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $newsArticles = $entityManager->getRepository(NewsArticle::class)->findAll();

        return $this->render('news/index.html.twig', [
            'newsArticles' => $newsArticles,
        ]);
    }

    #[Route('/news/new', name: 'app_news_new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADD');

        $newsArticle = new NewsArticle();
        $form = $this->createForm(NewsArticleType::class, $newsArticle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newsArticle = $form->getData();
            $newsArticle->setPublicationTime(new DateTime());
            $entityManager->getRepository(NewsArticle::class)->save($newsArticle);

            $this->addFlash('success', 'News article created!');

            return $this->redirectToRoute('app_news_show', ['id' => $newsArticle->getId()]);
        }

        return $this->render('news/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/news/edit/{id}', name: 'app_news_edit')]
    public function edit(NewsArticle $newsArticle, EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EDIT');

        $form = $this->createForm(NewsArticleType::class, $newsArticle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newsArticle = $form->getData();
            $entityManager->getRepository(NewsArticle::class)->save($newsArticle);

            $this->addFlash('success', 'News article updated!');

            return $this->redirectToRoute('app_news_show', ['id' => $newsArticle->getId()]);
        }

        return $this->render('news/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/news/{id}', name: 'app_news_show', methods: ['GET'])]
    public function show(NewsArticle $newsArticle): Response
    {
        return $this->render('news/show.html.twig', [
            'newsArticle' => $newsArticle,
        ]);
    }

    #[Route('/api/news', name: 'app_news_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, #[MapRequestPayload] CreateArticleRequestDto $articleDto): JsonResponse
    {
        $article = $this->getArticleFromCreateDto($articleDto);
        $entityManager->getRepository(NewsArticle::class)->save($article);
        return new JsonResponse(['id' => $article->getId()]);
    }

    #[Route('/api/news/{id}', name: 'app_news_post', methods: ['PUT'])]
    public function post(EntityManagerInterface $entityManager, int $id, #[MapRequestPayload] UpdateArticleRequestDto $articleDto): JsonResponse
    {
        $article = $entityManager->getRepository(NewsArticle::class)->find($id);
        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        if (!empty($articleDto->title)) {
            $article->setTitle($articleDto->title);
        }
        if (!empty($articleDto->content)) {
            $article->setContent($articleDto->content);
        }

        $entityManager->getRepository(NewsArticle::class)->save($article);
        return new JsonResponse(['id' => $article->getId()]);
    }

    #[Route('/api/news/{id}', name: 'app_news_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $entityManager->getRepository(NewsArticle::class)->deleteBeId($id);
        return new JsonResponse(['message' => 'Article deleted'], Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/news/{id}/comment', name: 'app_news_comment', methods: ['POST'])]
    public function comment(EntityManagerInterface $entityManager, Comment $comment, int $id): JsonResponse
    {
        $article = $entityManager->getRepository(Comment::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }
        $entityManager->getRepository(Comment::class)->save($comment);

        return new JsonResponse(['id' => $comment->getId()]);
    }

    #[Route('/api/news/{id}', name: 'app_news_comment_article', methods: ['POST'])]
    public function commentArticle(EntityManagerInterface $entityManager, #[MapRequestPayload]  CommentRequestDto $commentDto, int $id): JsonResponse
    {
        $article = $entityManager->getRepository(NewsArticle::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        $comment = $this->getCommentFromCommentDto($commentDto, $article);
        $entityManager->getRepository(Comment::class)->save($comment);

        return new JsonResponse(['id' => $comment->getId()]);
    }

    /**
     * @param CreateArticleRequestDto $articleDto
     * @return NewsArticle
     */
    private function getArticleFromCreateDto(CreateArticleRequestDto $articleDto): NewsArticle
    {
        $article = new NewsArticle();
        $article->setTitle($articleDto->title);
        $article->setContent($articleDto->content);
        $article->setPublicationTime(new DateTime());
        return $article;
    }

    /**
     * @param CommentRequestDto $commentDto
     * @param NewsArticle $article
     * @return Comment
     */
    private function getCommentFromCommentDto(CommentRequestDto $commentDto, NewsArticle $article): Comment
    {
        $comment = new Comment();
        $comment->setUser($commentDto->user);
        $comment->setContent($commentDto->content);
        $comment->setNewsArticle($article);
        return $comment;
    }
}
