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
    public function index(Environment $twig, VideoGameArticlesRepository $videoGameArticlesRepository, VideoGameReviewsRepository $videoGameReviewsRepository, UsersRepository $usersRepository): Response
    {
        // $queryBuilder = $entityManager->createQueryBuilder();

        // $queryBuilder
        // ->select('COUNT(vgr.id) as totalReviews', 'vga.id as articleId')
        // ->from('VideoGameReviews', 'vgr')
        // ->innerJoin('vgr', 'App\Entity\VideoGameArticles', 'vga', 'vgr.video_game_articles_id = vga.id')
        // ->groupBy('vga.id');
        
        // $subQuery = $queryBuilder->getDQL();
        
        // $queryBuilder = $entityManager->createQueryBuilder();
        
        // $queryBuilder
        // ->update('App\Entity\VideoGameArticles', 'vga')
        // ->set('vga.all_reviews', $subQuery);
        
        // $query = $queryBuilder->getQuery();
        // $query->execute();

        return new Response($twig->render('articles/index.html.twig', [
            'videoGameArticles' => $videoGameArticlesRepository->findAll(),
            'users' => $usersRepository->findAll()]));
    }

    #[Route('/article/{id}', name: 'article')]
    public function show(Environment $twig, VideoGameArticles $videoGameArticles, VideoGameReviewsRepository $videoGameReviews, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $review = new VideoGameReviews();
        $form = $this->createForm(VideoGameReviewsFormType::class, $review);
        $form->handleRequest($request);

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
            'videoGameReviews' => $videoGameReviews->findBy(['videoGameArticles' => $videoGameArticles])
        ]));
    }
}