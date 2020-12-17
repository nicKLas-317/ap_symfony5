<?php

namespace App\Controller;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $em): Response
    {

        // Lister les produits
        $products = $em->getRepository(Product::class)->findAll();
        // ou 
        // $em = $this->getDoctrine()->getManager();
        // $products =  $em->getRepository(Product::class)->findAll();

        return $this->render('home.html.twig', [
            'products' => $products,
            // 'title' => $title
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
     * @Route("/home", name="home")
     */
    // public function test(): Response
    // {
    //     // $formateur = "Yvon";
    //     // $val = "Hello et bienvenue sur la super page de tests, on va s'éclater avec $formateur ;)";
    //     // $title = "Bienvenue sur la page de test !";
      
    // }


     /**
     * @Route("/success", name="success")
     */
    public function success(): Response
    {
      return $this->render('success.html.twig', []);
    }


}
