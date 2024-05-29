<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Enter your email',
                    'class' => 'form-control',
                    'name'=> '_username'
                ],
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Enter your password',
                    'class' => 'form-control',
                    'name'=> '_password'
                ],
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => [
                    'placeholder'=> 'Enter your first name',
                    'class'=> 'form-control'
                ]
            ])->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => [
                    'placeholder'=> 'Enter your last name',
                    'class'=> 'form-control'
                ]
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Enter your phone number',
                'attr' => [
                    'placeholder'=> 'Enter your phone number',
                    'class'=> 'form-control'
                ]
            ])->add('birthDate', DateType::class, [
                'label' => 'Enter your birth date',
                'attr' => [
                    'placeholder'=> 'Enter your birth date',
                    'class'=> 'form-control'
                ]
            ]);

            if ($options['isAdmin']) {
                $builder
                    ->remove('password')
                    ->add('roles', ChoiceType::class, [
                        'label' => 'Roles',
                        'choices' => [
                            'User' => 'ROLE_USER',
                            'Admin' => 'ROLE_ADMIN',
                        ],
                        'multiple' => true,
                        'expanded' => true,
                        'attr' => [
                            'class' => 'form-control',
                        ],
                        'required' => 'required',
                    ])
                ;
            }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => User::class,
            'isAdmin'=> false,
        ]);
    }
}
