<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Account::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', "ID")->hideOnForm(),
            TextField::new('firstname',"Prénom"),
            TextField::new('lastname', "lastname"),
            TextField::new('email', "email"),
            TextField::new('password', "password"),
            AssociationField::new('role_id', "role_id"),

        ];
    }
    
}
