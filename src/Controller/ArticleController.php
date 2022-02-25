<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\NewArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    #[Route('/article/creer', name: 'new_article')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $article = new Article();
        $form = $this->createForm(NewArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $article->setAuthor($this->getUser());

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("home");
        }
        return $this->render('new_article/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/article/modifier/{id}', name: 'update_article', requirements: ['id ' => "\d+"])]
    public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if ($article->getAuthor() !== $this->getUser()){
           throw new AccessDeniedException();
        }

        $form = $this->createForm(NewArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager->flush();

            return $this->redirectToRoute("home");
        }
        return $this->render('update_article/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/supprimer/{id}', name: 'remove_article', requirements: ['id ' => "\d+"])]
    public function delete(Request $request,EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if ($article->getAuthor() !== $this->getUser()){
            throw new AccessDeniedException();
        }

        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute("home");
    }

    #[Route('/article/{id}', name: 'one_article', requirements: ['id ' => "\d+"])]
    public function oneArticle(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->findOneById($id);

        if ($article == null){
            throw new NotFoundHttpException();
        }

        return $this->render('one_article/index.html.twig', [
            'article' => $article
        ]);
    }
}
