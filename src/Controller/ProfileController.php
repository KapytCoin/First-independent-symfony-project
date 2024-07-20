<?php

namespace App\Controller;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Repository\VideoGameReviewsRepository;
use Twig\Environment;

class ProfileController extends AbstractController
{   
    #[Route('/profile', name: 'profile')]
    public function index(Environment $twig, UsersRepository $usersRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userNickname = $user->getNickname();
        $usersEmail = $user->getEmail();
        $userIsVerified = $user->IsVerified();
        $user = [
            'nickname' => "$userNickname",
            'email' => "$usersEmail",
            'isVerified' => "$userIsVerified"
        ];
        return new Response($twig->render('profile/profile.html.twig', ['user' => $user]));
    }
}
