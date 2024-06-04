<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom de la delivery',
                'attr' => [
                    'class'=>'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Description de la delivery',
                'attr'=> [
                    'class'=> 'form-control'
                ],
                'required'=>false
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix de la delivery',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            ->add('enable', CheckboxType::class, [
                'label'=> 'Activer la delivery',
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
