<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\EventSubscriber\UserSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email',EmailType::class)
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Administrateur' => ["ROLE_ADMIN"],
                'Modérateur' => ["ROLE_MODERATOR"],
                'Utilisateur' => ["ROLE_USER"],
            ],
            'multiple' => true,
            'expanded' => true,
            ])
            ->add('password', PasswordType::class, [
               // 'mapped' => false, // c'est moi qui vais prendre en charge la gestion de cette valeur quand je vais gérer le formumaire
            ])
        ->addEventSubscriber(new UserSubscriber())
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
