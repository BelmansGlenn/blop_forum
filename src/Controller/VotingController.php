<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\UpVote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VotingController extends AbstractController
{
    #[Route('/article/{id}/vote-positif', name: 'upVoting', requirements: ['id' => '\d+'])]
    public function upVote(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findOneById($id);
        $upvote = $entityManager->getRepository(UpVote::class)->findUpvoteByArticleIdAndUserId($id, $this->getUser());
        if ($upvote !== null){
            $entityManager->remove($upvote);
            $entityManager->flush();
            return $this->redirectToRoute('one_article', ['id' => $id]);
        }

        $voting = new UpVote();
        $voting->setUser($this->getUser());
        $voting->setArticle($article);
        $entityManager->persist($voting);
        $entityManager->flush();

        return $this->redirectToRoute('one_article', ['id' => $id]);
    }
}
