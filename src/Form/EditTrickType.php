<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'label' => false
            ])
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'placeholder' => 'Choisir un groupe',
                'label' => 'Groupe',
                'choice_label' => function (Group $Group) {
                    return $Group->getName();
                }, 'choice_value' => function ($Group) {
                    return $Group ? $Group->getId() : '';
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

