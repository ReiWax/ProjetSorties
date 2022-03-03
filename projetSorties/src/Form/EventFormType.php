<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Event;
use App\Entity\Location;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de la Sortie',
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('dateTimeStartAt', DateTimeType::class,[
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text', 
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('dateLimitRegistrationAt', DateTimeType::class,[
                'label' => 'Date limite d inscription',
                'widget' => 'single_text', 
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('duration', IntegerType::class,[
                'label' => 'Durée',
            ])
            ->add('nbMaxRegistration', IntegerType::class,[
                'label' => 'Nombre de places',
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Descriptions et Infos',
                'attr' => [
                    'placeholder' => ''
                ]
            ])
            ->add('adress', EntityType::class, [
                'label' => 'Choisir une adresse',
                'required' => true,
                'class' => Adress::class,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('location', EntityType::class, [
                'label' => 'Choisir un lieu',
                'required' => true,
                'class' => Location::class,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('state', EntityType::class, [
                'label' => 'Choisir un état',
                'required' => true,
                'class' => State::class,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
