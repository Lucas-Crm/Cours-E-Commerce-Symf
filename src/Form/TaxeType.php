<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaxeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la taxe',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
            ])
            ->add('rate', NumberType::class, [
                'label' => 'Rate de la taxe',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Activer la taxe',
                'required'  => false,
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
