<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\VideoGameReviews;
use Symfony\Component\HttpFoundation\Request;
use App\Form\VideoGameReviewsFormType;


class CommentController extends AbstractController
{
    #[Route('/commentsForm', name: 'comments')]
    public function comment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $review = new VideoGameReviews();
        $form = $this->createForm(VideoGameReviewsFormType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review->setGrade($form->get('grade')->getData());
            $review->setText($form->get('text')->getData());

            $entityManager->persist($review);
            $entityManager->flush();
        }
        
        return $this->render('comments/commentsForm.html.twig', [
        'form' => $form
        ]);
    }
}