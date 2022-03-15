<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\EventSubscriber\UserSubscriber;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email')
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Administrateur' => 'ROLE_ADMIN',
                'Modérateur' => 'ROLE_MODERATOR',
                'Utilisateur' => 'ROLE_USER',
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
