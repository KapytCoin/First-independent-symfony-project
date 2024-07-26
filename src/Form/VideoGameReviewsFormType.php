<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\VideoGameArticles;
use App\Entity\VideoGameReviews;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VideoGameReviewsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grade', IntegerType::class, [
                ])
            ->add('text', TextareaType::class, [
                ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('videoGameArticles', EntityType::class, [
                'class' => VideoGameArticles::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VideoGameReviews::class,
        ]);
    }
}
