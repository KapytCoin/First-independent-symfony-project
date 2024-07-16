<?php

namespace App\Controller\Admin;

use App\Entity\VideoGameReviews;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class VideoGameReviewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VideoGameReviews::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('отзыв')
            ->setEntityLabelInPlural('отзыв')
            ->setSearchFields(['users_id', 'text', 'grade'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('videoGameArticles'))
        ;
    }
    public function configureFields(string $pageName): iterable
    {
    yield AssociationField::new('videoGameArticles');
        yield TextField::new('users_id');
        yield TextField::new('grade');
        yield TextareaField::new('text')
            ->hideOnIndex()
        ;
        yield TextField::new('photoFilename')
            ->onlyOnIndex()
        ;
    }
}
