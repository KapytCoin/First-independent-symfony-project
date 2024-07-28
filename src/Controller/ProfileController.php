<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UsersRepository;
use Twig\Environment;
use App\Entity\Users;
use App\Form\AvatarTypeFormType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{   
    #[Route('/profile', name: 'profile')]
    public function index(Environment $twig, UsersRepository $usersRepository, Request $request,
    SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $avatarsDirectory
    ): Response
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

        $avatar = new Users();
        $form = $this->createForm(AvatarTypeFormType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarFile */
            $avatarFile = $form->get('avatars')->getData();

            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

                try {
                    $avatarFile->move($avatarsDirectory, $newFilename);
                } catch (FileException $e) {
                }

                $avatar->setAvatars($newFilename);
            }

            return $this->redirectToRoute('profile');
        }

        return new Response($twig->render('profile/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]));
    }
}
