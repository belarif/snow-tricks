<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'placeholder' => 'Choisir un groupe',
                'choice_label' => function (Group $Group) {
                    return $Group->getName();
                }, 'choice_value' => function ($Group) {
                    return $Group ? $Group->getId() : '';
                }])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('videos', CollectionType::class, [
                'allow_add' => true,
                'prototype' => true,
                'mapped' => false,
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => ['class' => 'text-box'],
                    'required' => false
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

