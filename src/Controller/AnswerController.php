<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answers/{id}/vote/{direction<up|down>}", methods="POST")
     */
    public function commentVote(Answer $answer, $direction, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        if ($direction === 'up') {
            $logger->info('Voting up!');
            $answer->upVote();
        } else {
            $logger->info('Voting down!');
            $answer->downVote();
        }
        $entityManager->flush();
        return $this->json(['votes' => $answer->getVotes()]);
    }

    /**
     * @Route("/answers/popular",name="app_popular_answers")
     */
    public function popularAnswers(AnswerRepository $answerRepository){
        $answers = $answerRepository->findMostPopular();

        return $this->render('answer/popular.html.twig',[
            'answers' => $answers
        ]);
    }
}
