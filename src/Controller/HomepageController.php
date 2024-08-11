<?php

namespace App\Controller;

use App\Entity\VideoGameArticles;
use App\Repository\VideoGameArticlesRepository;
use App\Repository\VideoGameReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, 
    PaginatorInterface $paginatorInterface, 
    EntityManagerInterface $entityManager, 
    Environment $twig, 
    UsersRepository $usersRepository,
    ): Response
    {
        $user = $this->getUser();

        if ($user !== null) {
        $role = $this->getUser()->getRoles();
        $getLastOnline = $user->getLastOnline();
        $serializeLastOnline = serialize($getLastOnline);
        $strstrLastOnline = strstr($serializeLastOnline, '2024');
        $wasLastOnline = strstr($strstrLastOnline, '00', true);
        $res = $user->setLastOnlineString($wasLastOnline);

        if (@$role[1] == 'ROLE_ADMIN' && @$role[2] == 'ROLE_USER' || @$role[1] == 'ROLE_USER' && @$role[2] == 'ROLE_USER') {
            $role = 'ROLE_ADMIN';
        } else {
            $role = 'ROLE_NOT_ADMIN';
        }

        $entityManager->persist($res);
        $entityManager->flush();
        } else {
            $role = 'ROLE_NOT_ADMIN';
        }

        $page = $request->get('page'); 
        $page = $page ? (int) $page : 1;

        $articles = $entityManager->getRepository(VideoGameArticles::class)->findAll();
        $videoGameArticles = $paginatorInterface->paginate($articles, $page, 3);

        $countReviews = $entityManager->getRepository(VideoGameArticles::class)->countReviewsAndAverageGrades();
        $path = 'uploads/' . '';

        return new Response($twig->render('articles/index.html.twig', [
            'videoGameArticles' => $videoGameArticles,
            'users' => $usersRepository->findAll(),
            'path' => $path,
            'isAdmin' => $role,
        ]));
    }

    #[Route('/article/{id}', name: 'article')]
    public function show(UsersRepository $usersRepository, 
    Environment $twig,
    VideoGameArticles $videoGameArticles, 
    VideoGameReviewsRepository $videoGameReviews, 
    EntityManagerInterface $entityManager, 
    Request $request): Response
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

    #[Route('/search', name: 'search_article')]
    public function searchArticle(Request $request,
    EntityManagerInterface $entityManager,
    Environment $twig,
    ): Response
    {
        $searchArticle = $request->query->get('search');
        $videoGameArticles = $entityManager->getRepository(VideoGameArticles::class)->findByName($searchArticle);
        
        if($videoGameArticles) {
            foreach($videoGameArticles as $item) {
                $findId = ($item->getId());
            }

        return $this->redirectToRoute('article', ['id' => $findId]);
        }

        return new Response($twig->render('articles/search.html.twig', [
            'search' => $searchArticle,
            'articles' => $videoGameArticles,
        ]));
    }

    #[Route('/profile/{nickname}', name: 'anotherProfile')]
    public function anotherProfile(Environment $twig, UsersRepository $usersRepository, Request $request)
    {
        $nickname = $request->attributes->get('nickname');
        $targetUser = $usersRepository->findOneBy(['nickname' => $nickname]);
        $pathAvatar = '../uploads/' . '';

        return new Response($twig->render('profile/anotherProfile.html.twig', [
            'targetUser' => $targetUser,
            'pathAvatar' => $pathAvatar
        ]));
    }
}