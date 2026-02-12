<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductToNameTransformer implements DataTransformerInterface
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    // Entity → string
    public function transform($product): string
    {
        return $product ? $product->getName() : '';
    }

    // string → Entity
    public function reverseTransform($productName): ?Product
    {
        if (!$productName) {
            return null;
        }

        $product = $this->repository->findOneBy(['name' => $productName]);

        if (!$product) {
            throw new TransformationFailedException('Product not found');
        }

        return $product;
    }
}
