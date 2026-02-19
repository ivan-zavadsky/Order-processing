<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\DataTransformerInterface;

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

        $product = $this->repository->findOneByName($productName);

        if (!$product) {
//            throw new TransformationFailedException('Product not found');
            $product = new Product();
            $product->setName('mock');
            $product->setPrice('0');
        }

        return $product;
    }
}
