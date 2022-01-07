<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('title', null, [
                'label' => 'Titre',
                'translation_domain' => false,
            ])
            ->add('leading', null, [
                'label' => 'Accroche',
                'translation_domain' => false,
            ])
            ->add('body', null, [
                'label' => 'Article',
                'translation_domain' => false,
                'attr' => [
                    'rows' => 10,
                ],
            ])
            ->add('createdBy', null, [
                'label' => 'Auteur',
                'translation_domain' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
