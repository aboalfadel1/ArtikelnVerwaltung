<?php

namespace App\Controller\Api;

use DateTime;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/api/articles', methods: ['GET'])]
    public function list(EntityManagerInterface $em){
        $artikeln = $em->getRepository(Article::class)->findAll();
        return $this->json($artikeln);
    }
    
    #[Route('/api/articles', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $article = new Article();
        $article->setTitle($data['title']);
        $article->setInhalt($data['inhalt']);
        $article->setCreatedAt(new \DateTimeImmutable());

        $em->persist($article);
        $em->flush();

        return $this->json($article, 201);
    }

    #[Route('/api/articles/{id}', methods: ['GET'])]
    public function show(Article $article): JsonResponse
    {
        return $this->json($article);
    }

    #[Route('/api/articles/{id}', methods: ['PUT'])]
    public function update(Request $request, Article $article, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $article->setTitle($data['title'] ?? $article->getTitle());
        $article->setInhalt($data['inhalt'] ?? $article->getInhalt());

        $em->flush();

        return $this->json($article);
    }

    #[Route('/api/articles/{id}', methods: ['DELETE'])]
    public function delete(Article $article, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($article);
        $em->flush();

        return $this->json(null, 204);
    }
}
