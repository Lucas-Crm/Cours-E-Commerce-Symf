<?php

namespace App\Form;

use App\Entity\Marque;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Description du produit',
                'attr'=> [
                    'class'=>'form-control'
                ]
            ])
            ->add('authenticity', TextareaType::class, [
                'label' => 'Authenticiter du produit',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Activer le produit',
            ])
            ->add('marque_id', EntityType::class, [
               'class'=> Marque::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder{
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.enable = true')
                        ->orderBy('m.name', 'ASC')
                        ;
                },
                'choice_label'=> 'name',
                'multiple' => false,
                'expanded' => false
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
