<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use App\Service\EventListener\MediaListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
	/**
	 * @var ParameterBagInterface
	 */
	private $parameterBag;

	public function __construct(ParameterBagInterface $parameterBag) {
		$this->parameterBag = $parameterBag;
	}

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
	    /** @var Trick $trick */
	    $trick = $options['data'];
		$nameFieldOptions = [];

		if ($trick->getId()) {
			$nameFieldOptions = [
				'mapped' => false,
                'disabled' => true
			];
		}
		$nameFieldOptions['label'] = 'Nom: ';

        $builder
            ->add('name', TextType::class, $nameFieldOptions)
            ->add('description', TextareaType::class, [
                'label' => 'Description :'
            ])
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'label' => 'Groupe :',
                'placeholder' => 'Choisir un groupe',
                'choice_label' => function (Group $Group) {
                    return $Group->getName();
                }, 'choice_value' => function ($Group) {
                    return $Group ? $Group->getId() : '';
                }])
            ->add('images', FileType::class, [
                'label' => 'Images :',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('videos', CollectionType::class, [
                'label' => false,
                'allow_add' => true,
                'prototype' => true,
                'mapped' => false,
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => ['class' => 'text-box'],
                    'required' => false
                ],
            ])
	        // ->addEventSubscriber(new MediaListener($this->parameterBag))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

