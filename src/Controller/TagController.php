<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 class TagController extends AbstractController
{
    #[Route('/api/tags', methods: ['GET'])]
    public function list(EntityManagerInterface $em){
        $tags = $em->getRepository(Tag::class)->findAll();
        return $this->json($tags);
    }
    

#[Route('/api/tags', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (empty($data['name'])) {
        return new JsonResponse(['error' => 'Tag name is required'], 400);
    }

    $tag = new Tag();
    $tag->setName($data['name']);

    $em->persist($tag);
    $em->flush();

    return new JsonResponse(['message' => 'Tag created successfully'], 201);
}

#[Route('/api/tag/{id}', methods: ['GET'])]

    #[Route('/api/tag/{id}', methods: ['GET'])]
    public function show(Tag $tag): JsonResponse
    {
        return $this->json($tag);
    }

    #[Route('/api/tag/{id}', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, Tag $tag): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tag->setName($data['name'] ?? $tag->getName());

        $em->flush();

        return new JsonResponse(['message' => 'Tag updated successfully']);
    }

    #[Route('/api/tag/{id}', methods: ['DELETE'])]
    public function delete(Tag $tag, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($tag);
        $em->flush();

        return $this->json(null, 204);
    }
}
