<?php

namespace App\Form;

use App\Entity\OrderItem;
use App\Entity\Product;
use Decimal\Decimal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'product',
                EntityType::class, [
                    'class' => Product::class,
                    'choice_label' => fn(Product $product) =>
                        $product->getName() . ': $' . $product->getPrice(),
                    'placeholder' => 'Выберите продукт',
//                    'autocomplete' => true,
                ])
            ->add('quantity',
                IntegerType::class, [
                        'attr' => ['min' => 1],
                ])
//            ->add(
//                'price',
//                MoneyType::class, [
//                    'currency' => 'RUR',
//                    'disabled' => true
//                ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class
        ]);
    }
}
