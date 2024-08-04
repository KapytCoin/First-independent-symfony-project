<?php

namespace App\Controller;

use App\Repository\VideoGameArticlesRepository;
use App\Entity\VideoGameArticles;
use App\Repository\VideoGameReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use App\Repository\UsersRepository;
use App\Entity\VideoGameReviews;
use Symfony\Component\HttpFoundation\Request;
use App\Form\VideoGameReviewsFormType;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager, Environment $twig, VideoGameArticlesRepository $videoGameArticlesRepository, VideoGameReviewsRepository $videoGameReviewsRepository, UsersRepository $usersRepository): Response
    {
        $countReviews = $entityManager->getRepository(VideoGameArticles::class)->countReviewsAndAverageGrades();
        $path = 'uploads/' . '';

        return new Response($twig->render('articles/index.html.twig', [
            'videoGameArticles' => $videoGameArticlesRepository->findAll(),
            'users' => $usersRepository->findAll(),
            'path' => $path
        ]));
    }

    #[Route('/article/{id}', name: 'article')]
    public function show(UsersRepository $usersRepository, Environment $twig, VideoGameArticles $videoGameArticles, VideoGameReviewsRepository $videoGameReviews, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $review = new VideoGameReviews();
        $form = $this->createForm(VideoGameReviewsFormType::class, $review);
        $form->handleRequest($request);
        $path = '../uploads/' . '';

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        elseif (in_array("ROLE_BAN", $user->getRoles())) {
            return $this->redirectToRoute('app_ban');
        }

        elseif ($form->isSubmitted() && $form->isValid()) {

            $review->setGrade($form->get('grade')->getData());
            $review->setText($form->get('text')->getData());
            $review->setUsers($user);
            $review->setVideoGameArticles($videoGameArticles);

            $entityManager->persist($review);
            $entityManager->flush();
        }

        return new Response($twig->render('articles/show.html.twig', [
            'form' => $form->createView(),
            'videoGameArticles' => $videoGameArticles,
            'videoGameReviews' => $videoGameReviews->findBy(['videoGameArticles' => $videoGameArticles]),
            'users' => $usersRepository->findAll(),
            'path' => $path,
        ]));
    }
}