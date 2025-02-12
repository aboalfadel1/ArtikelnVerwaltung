<?php

namespace App\Controller\Api;

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

class ArticleController extends AbstractController
{
  
    #[Route('/api/articles', methods: ['GET'])]
    public function list(EntityManagerInterface $em){
        $artikeln = $em->getRepository(Article::class)->findAll();
        return $this->json($artikeln);
    }
    
    #[Route('/api/articles', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em , TagRepository $tagRepository, AuthorRepository $authorRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $authorRepository = $em->getRepository(Author::class);
        $author = $authorRepository->find($data['author_id']);
        
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], 404);
        }

        $article = new Article();
        $article->setSlug($data['slug']);
        $article->setTitle($data['title']);
        $article->setDescription($data['description']);
        $article->setBody($data['body']);
        $article->setCreatedAt(new \DateTime());
        $article->setUpdatedAt(new \DateTime());
        $article->setFavorited($data['favorited']);
        $article->setFavoritesCount(0);
        $article->setAuthor($author);
        if (!empty($data['tags'])) {
            $tagRepository = $em->getRepository(Tag::class);
            foreach ($data['tags'] as $tagName) {
                $tag = $tagRepository->findOneBy(['name' => $tagName]);
                if ($tag) {
                    $article->addTag($tag);
                }
            }
            $em->flush();
        }


        $em->persist($article);
        $em->flush();

        return new JsonResponse(['message' => 'Article created successfully'], 201);
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
       
        $article->setSlug($data['slug']);
        $article->setTitle($data['title']);
        $article->setDescription($data['description']);
        $article->setBody($data['body']);
        $article->setCreatedAt(new \DateTime());
        $article->setUpdatedAt(new \DateTime());
        $article->setFavorited($data['favorited']);
        $article->setFavoritesCount(0);
        $author = $em->getRepository(Author::class)->find($data['author_id']);
        if ($author) {
            $article->setAuthor($author);
        }
        $article->setUpdatedAt(new \DateTime());
        $tagRepository = $em->getRepository(Tag::class);
        $article->clearTags();
        foreach ($data['tags'] as $tagName) {
            $tag = $tagRepository->findOneBy(['name' => $tagName]);
            if ($tag) {
                $article->addTag($tag);
            }
        }
        $em->flush();

        return new JsonResponse(['message' => 'Article updated successfully'], 200);
    }

    #[Route('/api/articles/{id}', methods: ['DELETE'])]
    public function delete(Article $article, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($article);
        $em->flush();

        return $this->json(null, 204);
    }
}
