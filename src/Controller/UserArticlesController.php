<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserArticlesController extends AbstractController
{
    #[Route('/articles/user/{id}', name: 'user_articles', requirements: ['id' => '\d+'])]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findWithAuthorId($id);
        return $this->render('user_articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
