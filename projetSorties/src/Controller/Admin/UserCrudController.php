<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('name'),
            TextField::new('lastname'),
            TextField::new('pseudo'),
            TelephoneField::new('tel'),
            BooleanField::new('admin'),
            BooleanField::new('active'),
            TextField::new('password')->setFormType(PasswordType::class),
            ImageField::new('illustration')
            ->setBasePath('uploads/')
            ->setUploadDir('public/uploads/images')
            ->setUploadedFileNamePattern("[randomhash].[extension]")
            ->setRequired(false),
            AssociationField::new('adress')
        ];
    }
    
}
