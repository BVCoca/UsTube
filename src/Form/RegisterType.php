<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'attr' =>
                    [
                        'class' => 'inputEmail'
                    ]
                ]
            )
            ->add('password', RepeatedType::class, [
                'constraints' => new Length([
                    'min' => 4,
                    'max' => 20
                ]),
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' =>
                    [
                        'class' => 'inputPassword'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' =>
                    [
                        'class' => 'inputPasswordConfirm'
                    ]
                ]
            ])
            ->add('avatar', FileType::class, array(
                'label' => false,
                'required'   => false,
            ))
            ->add('pseudo', TextType::class, [
                'constraints' => new Length([
                    'min' => 4,
                    'max' => 20
                ]),
                'label' => 'Pseudo',
                'attr' =>
                [
                    'class' => 'inputEmail'
                ]
            ])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'S\'inscrire',
                    'attr' =>
                    [
                        'class' => 'btn'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
