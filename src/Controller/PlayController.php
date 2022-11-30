<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Play;
use App\Entity\Quizz;
use App\Form\PlayType;
use App\Repository\AnswerRepository;
use App\Repository\PlayRepository;
use App\Repository\QuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/play')]
class PlayController extends AbstractController
{

    #[Route('/{id}', name: 'app_play', methods:['GET'])]
    public function index(
        Request $request, 
        Quizz $quizz, 
        QuestionsRepository $questionsRepository,
        PlayRepository $playRepository,
        AnswerRepository $answerRepository
        ): Response
    {
        if($this->getUser()->isHide() == true){
            return $this->render('user/userBan.html.twig', []);
        }

        $scoreMax = 0;

        $questions = $questionsRepository->findByQuizz($quizz);
        foreach ($questions as $question) {
            $quizz->addQuestion($question);
            $scoreMax += 1;
        }

        //Verifie si le joueurs a déjà jouer
        if(!is_null($played = $playRepository->findIfUserAlreadyPlayed($this->getUser(), $quizz))){
            return $this->render('play/result.html.twig', [
                'quizz' => $quizz,
                'score' => $played->getScoreUser(),
                'scoreMax' => $scoreMax,
                'classement' => array_reverse($playRepository->findBestPlayers($quizz))
            ]);
        }
        //Si le joueur n'a pas déjà jouer
        
        //On récupère les réponses
        if($request->query->all() != []){

            $userAnswers = $request->query->all();
            $score = $this->calculScore($userAnswers, $quizz);

            //On enregistre les réponses
            $play = new Play();
            $play->setPlayer($this->getUser())->setQuizz($quizz)->setScoreUser($score);
            $playRepository->save($play, true);
            
            foreach ($userAnswers as $key => $userAnswer) {
                $answer = new Answer();
                $answer->setPlayer($this->getUser())
                ->setQuestions($questionsRepository->findOneById(intval($key)))
                ->setAnswerUser($userAnswer);
                
                $answerRepository->save($answer,true);
            }
            
            return $this->render('play/result.html.twig', [
                'quizz' => $quizz,
                'score' => $score,
                'scoreMax' => $scoreMax,
                'classement' => array_reverse($playRepository->findBestPlayers($quizz))
            ]);
            
        }
        return $this->render('play/index.html.twig', [
            'quizz' => $quizz,
            'questions' => $questions,
        ]);
    }

    public function calculScore(Array $answered, Quizz $quizz)
    {
        $result = 0;
        foreach ($quizz->getQuestions() as $question) {
            if ($question->getCorrectAnswer() == $answered[$question->getId()]) {
                $result += 1;
            }
        }
        return $result;
    }
    





    #[Route('/new', name: 'app_play_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayRepository $playRepository): Response
    {
        $play = new Play();
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playRepository->save($play, true);

            return $this->redirectToRoute('app_play_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('play/new.html.twig', [
            'play' => $play,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_play_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Play $play, PlayRepository $playRepository): Response
    {
        $form = $this->createForm(PlayType::class, $play);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playRepository->save($play, true);

            return $this->redirectToRoute('app_play_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('play/edit.html.twig', [
            'play' => $play,
            'form' => $form,
        ]);
    }
}
