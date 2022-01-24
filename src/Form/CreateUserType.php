<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
            ])/*->add('role', EntityType::class, [
                    'attr' => ['class' => 'form-control'],
                    'class' => Role::class,
                    'placeholder' => 'Choisir un rôle',
                    'choice_label' => function (Role $Role) {
                        return $Role->getRole();
                    }, 'choice_value' => function ($Role) {
                        return $Role ? $Role->getId() : '';
                    }]
            )*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
