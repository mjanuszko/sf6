<?php

namespace App\Controller;

use App\DTO\CommentRequestDto;
use App\DTO\CreateArticleRequestDto;
use App\DTO\UpdateArticleRequestDto;
use App\Entity\Comment;
use App\Entity\NewsArticle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/api/news/{id}', name: 'app_news_edit', methods: ['PUT'])]
    public function edit(EntityManagerInterface $entityManager, int $id, #[MapRequestPayload] UpdateArticleRequestDto $articleDto): JsonResponse
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
        $article->setPublicationTime(new \DateTime());
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
