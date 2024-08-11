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

        $findCurrentlyFriendship = $friendshipRepository->findOneBy(['sendingUserId' => $sendingUser, 'acceptingUserId' => $targetUserId]);
        $serializeFindCurrentlyFriendship = serialize($findCurrentlyFriendship);
        
        $currentlyFriendship = strstr($serializeFindCurrentlyFriendship, 'Request sent');
        $statusSent = strstr($currentlyFriendship, '";', true);

        $statusAccept = strstr($serializeFindCurrentlyFriendship, 'Request approved');
        $statusAccept = strstr($statusAccept, '";', true);

        $statusRejected = strstr($serializeFindCurrentlyFriendship, 'Request rejected');
        $statusRejected = strstr($statusRejected, '";', true);

        if ($post) {
        $newFriend->setSendingUserId($sendingUser);
        $newFriend->setAcceptingUserId($targetUserId);
        $newFriend->setStatus('Request sent');

        $entityManager->persist($newFriend);
        $entityManager->flush();

        return $this->redirectToRoute('anotherProfile', ['nickname' => $nickname]); // Осталось сделать остальные два варианта с отображением в homepage
                                                                                    // где таргет юзер отклоняет или принимает заявку в друзья
        } elseif (1 == 3) {
            $newFriend->setSendingUserId($sendingUser);
            $newFriend->setAcceptingUserId($targetUserId);
            $newFriend->setStatus($statusSent);
    
            $entityManager->persist($newFriend);
            $entityManager->flush();
            } elseif (1 == 2) {
                $newFriend->setSendingUserId($sendingUser);
                $newFriend->setAcceptingUserId($targetUserId);
                $newFriend->setStatus($statusSent);
    
                $entityManager->persist($newFriend);
                $entityManager->flush();
        }

        return new Response($twig->render('profile/anotherProfile.html.twig', [
            'targetUser' => $targetUser,
            'pathAvatar' => $pathAvatar,
            'friendship' => $friendshipRepository->findAll(),
            'users' => $usersRepository->findOneBy(['nickname' => $nickname]),
            'statusSent' => $statusSent,
            'statusAccept' => $statusAccept,
            'statusRejected' => $statusRejected
        ]));
    }
}
