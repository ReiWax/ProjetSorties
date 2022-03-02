<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,  [
                'required'=>false,
                'label' => 'Le nom de la sortie contient : '
                ])
            ->add('dateTimeStartAt', DateTimeType::class, [
                'label' => 'Date de la sortie',
                'required'=>false,
            ])
            ->add('dateLimitRegistrationAt',  DateTimeType::class, [
                'label' => 'Clôture',
                'required'=>false
            ])
            ->add('eventIsOrganizer', CheckboxType::class, [
                'required'=>false,
                'label' => 'Sorties dont je suis l\'orgarnisateur/trice'
                ])
            ->add('eventIsRegistered', CheckboxType::class, [
                'required'=>false,
                'label' => 'Sorties auxquelles je suis inscrit/e'
                ])
            ->add('eventIsNotRegistered', CheckboxType::class, [
                'required'=>false,
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e'
                ])
            ->add('eventFinished', CheckboxType::class, [
                'required'=>false,
                'label' => 'Sorties passées'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}