<?php

namespace App\Controller;

use DateTime;
use App\Entity\Tag;
use App\Entity\Author;
use App\Entity\Article;

use Doctrine\ORM\EntityManager;
use App\Repository\TagRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 class AuthorController extends AbstractController
{
    #[Route('/api/authors', methods: ['GET'])]
    public function list(EntityManagerInterface $em){
        $authors = $em->getRepository(Author::class)->findAll();
        return $this->json($authors);
    }
    
    #[Route('/api/authors', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em  ):JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $author = new Author();
        $author->setUsername($data['username']);
        $author->setBio($data['bio']);
        $author->setImage($data['image']);
        $em->persist($author);
        $em->flush();

        return new JsonResponse(['message' => 'Author created successfully'], 201);
    }

    #[Route('/api/author/{id}', methods: ['GET'])]
    public function show(Author $author): JsonResponse
    {
        return $this->json($author);
    }

    #[Route('/api/author/{id}', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, Author $author): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $author->setUsername($data['username'] ?? $author->getUsername());
        $author->setBio($data['bio'] ?? $author->getBio());
        $author->setImage($data['image'] ?? $author->getImage());

        $em->flush();

        return new JsonResponse(['message' => 'Author updated successfully']);
    }

    #[Route('/api/author/{id}', methods: ['DELETE'])]
    public function delete(Author $author, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($author);
        $em->flush();

        return $this->json(null, 204);
    }
}
