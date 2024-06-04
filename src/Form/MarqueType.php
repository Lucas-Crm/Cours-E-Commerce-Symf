<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class MarqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Enter un nom de marque',
                'attr'=>[
                    'class'=> 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la marque',
                'attr' => [
                  'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('enable', CheckboxType::class, [
               'label'=>'Activer la marque',
                'required' => false
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
