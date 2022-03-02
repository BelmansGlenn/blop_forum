<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Article;
use App\Form\AnswerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Flasher\Prime\FlasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/article/reponse/{id}', name: 'answer', requirements: ['id' => '\d+'])]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $answer = new Answer();
        $article = $entityManager->getRepository(Article::class)->findOneBy(['id' => $id]);
        if ($article === null){
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(AnswerFormType::class, $answer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $answer->setAuthorCom($this->getUser());
            $answer->setArticle($article);
            $entityManager->persist($answer);
            $entityManager->flush();
            return $this->redirectToRoute('one_article', ['id' => $id]);
        }
        return $this->render('answer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/reponse/modifier/{id}', name: 'answer_update', requirements: ['id' => '\d+'])]
    public function updateAnswer(Request $request, EntityManagerInterface $entityManager, int $id, FlasherInterface $flasher): Response
    {
        $answer = $entityManager->getRepository(Answer::class)->find($id);

        if ($answer->getAuthorCom() !== $this->getUser()){
            throw new AccessDeniedException();
        }
        $form = $this->createForm(AnswerFormType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();

            $articleId = $answer->getArticle()->getId();
            $flasher->addSuccess('Votre commentaire a bien ete modifie');
            return $this->redirectToRoute('one_article', ['id' => $articleId]);
        }

        return $this->render('answer_update/index.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/article/reponse/supprimer/{id}', name: 'answer_delete', requirements: ['id' => '\d+'])]
    public function deleteAnswer(Request $request, EntityManagerInterface $entityManager, int $id, FlasherInterface $flasher): Response
    {
        $answer = $entityManager->getRepository(Answer::class)->find($id);
        $articleId = $answer->getArticle()->getId();
        if ($answer->getAuthorCom() !== $this->getUser()){
            throw new AccessDeniedException();
        }
        $entityManager->remove($answer);
        $entityManager->flush();

        $flasher->addSuccess('Votre commentaire a bien ete supprime');
        return $this->redirectToRoute('one_article', ['id' => $articleId]);
    }



}
