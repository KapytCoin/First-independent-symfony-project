<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Repository\FriendshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;

class AnotherProfileController extends AbstractController
{
    #[Route('/profile/{nickname}', name: 'anotherProfile')]
    public function anotherProfile(Environment $twig,
    UsersRepository $usersRepository,
    Request $request,
    FriendshipRepository $friendshipRepository,
    EntityManagerInterface $entityManager
    ): Response
    {
        $nickname = $request->attributes->get('nickname');
        $targetUser = $usersRepository->findOneBy(['nickname' => $nickname]);

        $pathAvatar = '../uploads/' . '';

        $user = $this->getUser();
        $sendingUser = $user->getId();
        $targetUserId = $targetUser->getId();
        $newFriend = new Friendship;

        $post = $request->request->all();
        $post = implode('', $post);

        $findCurrentlyFriendship = $friendshipRepository->findOneBy(['sendingUserId' => $sendingUser, 'acceptingUserId' => $targetUserId]);
        $friendshipNotification = $friendshipRepository->findOneBy(['sendingUserId' => $targetUser, 'acceptingUserId' => $sendingUser]);

        if ($post === 'Запрос в друзья') {
            $newFriend->setSendingUserId($sendingUser);
            $newFriend->setAcceptingUserId($targetUserId);
            $newFriend->setStatus('Request sent');

            $entityManager->persist($newFriend);
            $entityManager->flush();

        return $this->redirectToRoute('anotherProfile', ['nickname' => $nickname]);
        }

        if ($post === 'Повторный запрос в друзья') {
            $entityManager->remove($friendshipNotification);
            $entityManager->flush();

            $newFriend->setSendingUserId($sendingUser);
            $newFriend->setAcceptingUserId($targetUserId);
            $newFriend->setStatus('Request sent');

            $entityManager->persist($newFriend);
            $entityManager->flush();

        return $this->redirectToRoute('anotherProfile', ['nickname' => $nickname]);
        }

        if ($post === 'Удалить из друзей') {
            If ($friendshipNotification) {
            $entityManager->remove($friendshipNotification);
            $entityManager->flush();

            return $this->redirectToRoute('anotherProfile', ['nickname' => $nickname]);
            }
        }

        if ($friendshipNotification) {
            $currentlyStatusFriendship = $friendshipNotification->getStatus();

            return new Response($twig->render('profile/anotherProfile.html.twig', [
                'targetUser' => $targetUser,
                'pathAvatar' => $pathAvatar,
                'friendship' => $friendshipRepository->findAll(),
                'users' => $usersRepository->findOneBy(['nickname' => $nickname]),
                'statusFriendship' => $currentlyStatusFriendship,
            ]));
        }

        if ($findCurrentlyFriendship) {
            $currentlyStatusFriendship = $findCurrentlyFriendship->getStatus();

            return new Response($twig->render('profile/anotherProfile.html.twig', [
                'targetUser' => $targetUser,
                'pathAvatar' => $pathAvatar,
                'friendship' => $friendshipRepository->findAll(),
                'users' => $usersRepository->findOneBy(['nickname' => $nickname]),
                'statusFriendship' => $currentlyStatusFriendship,
            ]));
        }

        return new Response($twig->render('profile/anotherProfile.html.twig', [
            'targetUser' => $targetUser,
            'pathAvatar' => $pathAvatar,
            'friendship' => $friendshipRepository->findAll(),
            'users' => $usersRepository->findOneBy(['nickname' => $nickname]),
        ]));
    }
}
