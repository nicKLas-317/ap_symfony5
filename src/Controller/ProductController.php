<?php

namespace App\Controller;

use App\Entity\Product;

use App\Form\ProductFormType;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
     * @Route("/admin/product/add", name="ajoutProduct")
     */
    public function addProduct(KernelInterface $appKernel, EntityManagerInterface $em, SluggerInterface $slugger, Request $request)
    {  
        // $path = $appKernel->getProjectDir() . '/public/image';
        $product = new Product;

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
    
           
            if($form->isSubmitted() && $form->isValid()){
                // Génération du slug
                $product->setSlug($slugger->slug($product->getName()));

                // Traitement image
                // $file = $form['image']->getData();
                // if($file){
                //     // récup nom de fichier sans extension
                //     $origineFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                //     $newFileName = $origineFileName . '-' . uniqid() . '.' . $file->guessExtension();
                //     $product->setImage($newFileName);

                //     // Déplacer ds répertoire public/images
                //     try{
                //         $file->move(
                //             $path, $newFileName
                //         );
                //     }catch(FileException $e){
                //         echo $e->getMessage()();
                //     }
                // }
                $em->persist($product);
                $em->flush();

                return $this->redirectToRoute('produits');

            }

            return $this->render('product/product-form.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="editProduct")
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
     * @Route("/admin/product/delete/{id}", name= "deleteProduct")
     */
    public function deleteProduct(EntityManagerInterface $em, ProductRepository $productRepository, $id)
    {
        $product = $em->getRepository(Product::class)->find($id);
  
        $em->remove($product);
        $em->flush();
    
        return $this->redirectToRoute('produits');
      
    }
}