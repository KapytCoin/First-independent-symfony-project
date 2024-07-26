<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\Length;
use App\Entity\VideoGameReviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class VideoGameReviewsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grade', ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('text', TextareaType::class, [
            'constraints' => [
                new Length([
                    'min' => 10,
                    'minMessage' => 'Комментарий должен быть больше {{ limit }} символов',
                    'max' => 700,
                    'maxMessage' => 'Комментарий не должен превышать {{ limit }} символов'
                    ])]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VideoGameReviews::class,
        ]);
    }
}
