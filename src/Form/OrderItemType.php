<?php

namespace App\Form;

use App\Entity\OrderItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\ProductToNameTransformer;

class OrderItemType extends AbstractType
{
    private ProductToNameTransformer $transformer;

    public function __construct(ProductToNameTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'product',
                TextType::class, [
                    'label' => 'Выберите продукт',
                ])
            ->add('quantity',
                IntegerType::class, [
                        'attr' => ['min' => 1],
                ])
        ;

        $builder->get('product')
            ->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class
        ]);
    }
}
