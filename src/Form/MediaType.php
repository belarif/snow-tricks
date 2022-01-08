<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('src')
            ->add('trick', EntityType::class, ['class' => Trick::class, 'choice_label' => 'id'])
            ->add('type', EntityType::class, ['class' => Type::class, 'choice_label' => 'id']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
