<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Enter your email',
                    'class' => 'login-input',
                    'name'=> '_username'
                ],
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Enter your password',
                    'class' => 'login-input',
                    'name'=> '_password'
                ],
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'attr' => [
                    'placeholder'=> 'Enter your first name',
                    'class'=> 'login-input'
                ],
                'required' => false
            ])->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'attr' => [
                    'placeholder'=> 'Enter your last name',
                    'class'=> 'login-input'
                ],
                'required' => false
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Enter your phone number',
                'attr' => [
                    'placeholder'=> 'Enter your phone number',
                    'class'=> 'login-input'
                ],
                'required' => false
            ])->add('birthDate', DateType::class, [
                'label' => 'Enter your birth date',
                'attr' => [
                    'placeholder'=> 'Enter your birth date',
                    'class'=> 'login-input'
                ],
                'required' => false
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
                            'class' => '',
                        ],
                        'required' => 'required',
                    ])
                ;
            }
            if ($options['isRegister']){
                $builder
                    ->remove('firstName')
                    ->remove('lastName')
                    ->remove('telephone')
                    ->remove('birthDate')
                    ->remove('password')
                    ->add('password', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'required' => true,
                        'first_options' => [
                            'label' => 'Password',
                            'attr' => [
                                'placeholder' => 'Enter your password',
                                'class' => 'login-input',
                            ],
                            'constraints' => [
                                new NotBlank(),
                                new Length([
                                    'max' => 4096,
                                ]),
                                new Regex([
                                    'pattern' => '/^.*(?=.{8,120})(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\-\=\¡\£\_\+\`\~\.\,\<\>\/\?\;\:\'\"\\\|\[\]\{\}]).*$/',
                                    'message' => 'bad pwd',
                                ]),
                            ],
                        ],
                        'second_options' => [
                            'label' => 'Confirm Password',
                            'attr' => [
                                'placeholder' => 'Confirm your password',
                                'class' => 'login-input',
                            ],
                        ],
                        'mapped' => false,
                    ]);
            }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => User::class,
            'isAdmin'=> false,
            'isRegister'=> false
        ]);
    }
}
