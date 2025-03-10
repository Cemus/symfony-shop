<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id',"ID")->hideOnForm(),
            AssociationField::new('author', "Auteur (author_id)")->hideOnIndex()->setFormTypeOptions([
                'choice_label' => function ($author){
                    return $author->getId() . " - " . $author->getFirstname() . " " . $author->getLastname();
                }]),
            TextField::new('title', "Titre (title)"),
            TextField::new('content', "Contenu (content)")->setMaxLength(25),
            DateField::new('createAt', "Date de création (createAt)"),
            AssociationField::new('categories', "Catégories")->hideOnIndex()
        ];
    }
    
}
