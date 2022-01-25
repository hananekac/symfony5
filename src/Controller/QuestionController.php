<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    /**
     * @Route("/{page<\d+>}", name="app_homepage")
     */
    public function homepage(QuestionRepository $repository, $page=1)
    {
        //$questions = $repository->findAllPublishedQuestion();
        $adapter = new QueryAdapter($repository->publishedQuestionsQueryBuilder());
        $questions = new Pagerfanta($adapter);
        $questions->setMaxPerPage(5);
        $questions->setCurrentPage($page);
        return $this->render('question/homepage.html.twig', ['questions' => $questions]);
    }

    /**
     * @Route ("/questions/new", name="app_question_create")
     */
    public function new(){

        /*
         * TODO
         */
        return new Response(sprintf('Homepage : website for witches and wizards !! New code to be added soon'));
    }
    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question)
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        return $this->render('question/show.html.twig', [
            'question' => $question
        ]);
    }

    /**
     * @param Question $question
     * @param Request $request
     * @Route ("/questions/{slug}/vote", name="app_question_vote", methods={"POST"})
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager){
        $direction = $request->request->get('direction');
        if ($direction === 'up'){
            $question->upVote();
        }elseif( $direction === 'down'){
            $question->downVote();
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_question_show', ['slug' => $question->getSlug()]);

    }
}
