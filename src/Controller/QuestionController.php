<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="app_homepage")
     */
    public function homepage(QuestionRepository $repository)
    {
        $questions = $repository->findBy([], ['askedAt' => 'DESC']);
        return $this->render('question/homepage.html.twig', ['questions' => $questions]);
    }

    /**
     * @Route ("/questions/new", name="app_question_create")
     */
    public function new(EntityManagerInterface $entityManager){
        $question = new Question();
        $random = rand(1,100);
        $question->setName("question n°".$random);
        $question->setSlug("question-n-".$random);
        $question->setQuestion(<<< EOF
Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
to make my dishes wash themselves. But while I was casting it,
I slipped a little and I think `I also hit my pants with the spell`.
When I woke up this morning, I caught a quick glimpse of my pants
opening the front door and walking out! I've been out all afternoon
(with no pants mind you) searching for them.
Does anyone have a spell to call your pants back?
EOF
        );

        if ($random > 30) {
            $question->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))));
        }

        $entityManager->persist($question);
        $entityManager->flush($question);
        return new Response(sprintf('id object : %d  question : %s',$question->getId(), $question->getName()));
    }
    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper, EntityManagerInterface $entityManager)
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }
        $repository = $entityManager->getRepository(Question::class);
        $question = $repository->findOneBy(['slug'=> $slug]);

        if(!$question)
            throw $this->createNotFoundException('404 Not found !');
        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}
