<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Saisir le titre de l\'article'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'placeholder' => 'Saisir le contenu de l\'article'
                ]
            ])
            ->add('createAt', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur',
                'class' => Account::class,
                'choice_label' => 'id',
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories',
                'class' => Category::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])->add('save', SubmitType::class, [
                    'label' => 'Soumettre le compte',
                    'attr' => [
                        'class' => 'bg-green-500 text-white px-2 py-1 rounded-md hover:px-3 hover:py-2 hover:bg-green-400'
                    ]
                ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
