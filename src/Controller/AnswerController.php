<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Article;
use App\Form\AnswerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/article/answer/{id}', name: 'answer', requirements: ['id' => '\d+'])]
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
}
