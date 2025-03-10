<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Prénom',
                'attr'=>[
                    'placeholder' => 'Saisir votre prénom',
                ]
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom',
                'attr'=>[
                    'placeholder' => 'Saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Courriel',
                'attr'=>[
                    'placeholder' => 'Saisir votre adresse mail'
                ]
            ])
            ->add('password', RepeatedType::class,[
                'first_options' => ['label' => 'Mot de passe',
                'attr'=>[
                    'placeholder' => 'Saisir le mot de passe'
                ]],
                'second_options' => ['label' => 'Confirmer le mot de passe',
                'attr'=>[
                    'placeholder' => 'Saisir le mot de passe'
                ]]
                
            ])
            ->add('roles', TextType::class,[
                'label' => 'Rôle',
                'attr'=>[
                    'placeholder' => 'Saisir le rôle de l\'utilisateur'
                ]
            ])   
            ->add('save',SubmitType::class,[
                'label' => 'Soumettre le compte',
                'attr'=>[
                    'class' => 'bg-green-500 text-white px-2 py-1 rounded-md hover:px-3 hover:py-2 hover:bg-green-400'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
