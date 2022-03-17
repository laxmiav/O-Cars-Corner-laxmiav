<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class Car1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('model', TextType::class, [
            'label' => 'Model',
        ])
        ->add('releaseYear', DateType::class, [
            'label' => 'Released year',
            'widget' => 'single_text',
            'input' => 'datetime_immutable',
            'data' => new \DateTimeImmutable(),
        ])
        ->add('brands', EntityType::class, [ 'class' => Brand::class,
        'choice_label' => 'name',
       'expanded' => true,
       'multiple' => false,
        'choice_value' => 'id'
        ])
        ->add('type', ChoiceType::class, [
            'label' => 'Type',
            'choices' => [
                'Manual' => 'Manual',
                'Automatic' => 'Automatic',
                'Semi-Automatic' => 'Semi-Automatic',
                
            ],
            // Choix multiple : Cela va retourner un tableau
            'multiple' => false,
            // On veut un "widget HTML" par choix => checkboxes car multiple=true
            'expanded' => true,
        ])
        ->add('price')
        ->add('fuelType', ChoiceType::class, [
            'label' => 'Type',
            'choices' => [
                'Petrol' => 'Petrol',
                'Diesal' => 'Diesal',
                'Electric' => 'Electric',
                'Hybrid' => 'Hybrid',
                
            ],
            // Choix multiple : Cela va retourner un tableau
            'multiple' => false,
            // On veut un "widget HTML" par choix => checkboxes car multiple=true
            'expanded' => true,
        ])

    

        ->add('imageFile', FileType::class, [
                
            'mapped'=>false,
            'required' => false,
            
        ])
        ->add('seats', NumberType::class)
        ->add('otherSpec', TextareaType::class, [
            'label' => 'Any other Specifications',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
