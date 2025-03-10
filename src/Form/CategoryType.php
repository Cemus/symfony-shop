<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie',
                'attr' => [
                    'placeholder' => 'Saisir le nom de la catégorie'
                ]
            ])->add('save', SubmitType::class, [
                    'label' => 'Soumettre la catégorie',
                    'attr' => [
                        'class' => 'bg-green-500 text-white px-2 py-1 rounded-md hover:px-3 hover:py-2 hover:bg-green-400'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
