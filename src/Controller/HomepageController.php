<?php

namespace App\Controller;

use App\Repository\VideoGameArticlesRepository;
use App\Entity\VideoGameArticles;
use App\Repository\VideoGameReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use App\Repository\UsersRepository;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Environment $twig, VideoGameArticlesRepository $videoGameArticlesRepository, UsersRepository $usersRepository): Response
    {
        return new Response($twig->render('articles/index.html.twig', ['videoGameArticles' => $videoGameArticlesRepository->findAll(),
        'users' => $usersRepository->findAll()]));
    }

    #[Route('/article/{id}', name: 'article')]
    public function show(Environment $twig, VideoGameArticles $videoGameArticles, VideoGameReviewsRepository $videoGameReviews): Response
    {
            return new Response($twig->render('articles/show.html.twig', ['videoGameArticles' => $videoGameArticles, 
            'videoGameReviews' => $videoGameReviews->findBy(['videoGameArticles' => $videoGameArticles])]));
    }
}