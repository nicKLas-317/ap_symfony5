<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $category =  $manager->getRepository(Category::class)->find(1);
        $product = new Product();
        $product->setName('LOL')
        ->setPrice(1)
        ->setImage('malygos-5fdcccd193f85.jpg')
        ->setCategory($category);

        $manager->persist($product);
        $manager->flush();
    }
}
