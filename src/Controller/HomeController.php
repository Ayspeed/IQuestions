<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\QuizzRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:['GET'])]
    public function index(Request $request, QuizzRepository $quizzRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'quizzs' => $quizzRepository->AddfiltersOnQuizz($request->query->all()),
            'themes' => $quizzRepository->findAllTheme(),
            'difficulties' => $quizzRepository->findAllDifficulty(),
        ]);
    }

    #[Route('/infos', name: 'app_infos')]
    public function index1(): Response
    {
        return $this->render('home/infos.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function index2(): Response
    {
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
