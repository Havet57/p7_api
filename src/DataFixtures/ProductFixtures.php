<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productsData=[
            ['id'=>1, 'name'=>'ophone', 'description'=>"nian nian nian ce produit nian nian", 'price'=>685],
            ['id'=>2, 'name'=>'sumsung', 'description'=>" tip top nian nian nian ce produit tip top nian nian", 'price'=>1300],
           
        ];
        foreach($productsData as $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);
            $this->setReference('product-'.$productData['id'], $product);

            $manager->persist($product);
        }
        $manager->flush();
    }
}
