<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom du Lieux',
                'required' => true
            ])
            ->add('street', TextType::class,[
                'label' => 'Rue',
                'required' => true
            ])
            ->add('lat', NumberType::class,[
                'label' => 'Latitude',
                'required' => false
            ])
            ->add('long', NumberType::class,[
                'label' => 'Longitude',
                'required' => false
            ])
            ->add('city', EntityType::class,[
                'label' => 'Ville',
                'required' => true,
                'class' => City::class
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
