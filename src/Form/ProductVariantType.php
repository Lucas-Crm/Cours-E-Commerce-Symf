<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Taxe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', NumberType::class, [
                'label' => 'Prix du Product Variant',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('size', NumberType::class, [
                'label' => 'Taille du product variant',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('taxe', EntityType::class, [
                'label' => 'Taxe du produit',
                'class' => Taxe::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder{
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.enable == true')
                        ->orderBy('t.rate', 'ASC');
                },
                'choice_label' => 'price',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('product', EntityType::class, [
                'label' => 'Produit liee au product variant',
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder{
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.enable == true')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
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
