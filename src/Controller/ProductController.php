<?php

namespace App\Controller;

use App\Entity\Product;

use App\Form\ProductFormType;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

     /**
     * @Route("/products", name="produits")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
  
        return $this->render('product/allProducts.html.twig', [
            'products' => $products
        ]);
    }

      /**
     * @Route("/product/{id}-{slug}", name="detailProduit")
     */
    public function detailProduct(EntityManagerInterface $em, $id): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        return $this->render('product/productDetail.html.twig', [
            'product' => $product
        ]);
    }


    /**
     * @Route("/product/add", name="ajoutProduct")
     */
    public function addProduct(EntityManagerInterface $em, SluggerInterface $slugger, Request $request)
    {  
        $product = new Product;

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
    
           
            if($form->isSubmitted() && $form->isValid()){
                // Génération du slug
                $product->setSlug($slugger->slug($product->getName()));

                $em->persist($product);
                $em->flush();

                return $this->redirectToRoute('produits');

            }

            return $this->render('product/product-form.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/product/edit/{id}", name="editProduct")
     */
    public function editProduct(EntityManagerInterface $em, SluggerInterface $slugger,Request $request, $id)
    {
    //    Get product with id
            $product = $em->getRepository(Product::class)->find($id);
            
            $form = $this->createForm(ProductFormType::class, $product);
            $form->handleRequest($request);
           

            if($form->isSubmitted() && $form->isValid()){
                $product->setSlug($slugger->slug($product->getName()));

                $em->persist($product);
                $em->flush();

                return $this->redirectToRoute('produits');

            }

            return $this->render('product/edit.html.twig', [
                'form' => $form->createView(),
            ]);
    }


     /**
     * @Route("product/delete/{id}", name= "deleteProduct")
     */
    public function deleteProduct(EntityManagerInterface $em, ProductRepository $productRepository, $id)
    {
        $product = $em->getRepository(Product::class)->find($id);
  
        $em->remove($product);
        $em->flush();
    
        return $this->redirectToRoute('produits');
      
    }
}