<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('src', FileType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm',
                    'id' => 'editImage'
                ]
            ])
            ->add(
                'envoyer',
                SubmitType::class,
                [
                    'attr' => [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'id' => 'editImage'
                    ]]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
