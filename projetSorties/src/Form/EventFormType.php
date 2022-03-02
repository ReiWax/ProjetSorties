<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('duration', IntegerType::class,[
                'label' => 'Durée',
            ])
            ->add('dateLimitRegistrationAt', DateTimeType::class,[
                'label' => 'Date limite d inscription',
                'widget' => 'single_text', 
                'attr' => [
                    'placeholder' => ''
                ]
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
