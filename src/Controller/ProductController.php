<?php

namespace App\Controller;

use App\Entity\Product;

use App\Entity\Category;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/add", name="ajoutProduct")
     */
    public function addProduct(FormFactoryInterface $factory, Request $request){
      
        $product = new Product;
      
        $builder =$factory->createBuilder();
        $builder->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('slug', TextType::class)
            ->add('category', 
            EntityType::class,
                [
                    'class'  => Category::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Choisir une catégorie',
                    'label' => 'Catégorie',
                ]
            )
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter Produit'
            ]);


            $form = $builder->getForm();
       
            return $this->render('product/index.html.twig', [
                'form' => $form->createView(),
            ]);
    }
}
