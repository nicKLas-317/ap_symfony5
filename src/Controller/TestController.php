<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class TestController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
        ]);

      
    }

    /**
     * @Route("/home", name="home")
     */
    public function test(ProductRepository $productRepository): Response
    {
        // $formateur = "Yvon";
        // $val = "Hello et bienvenue sur la super page de tests, on va s'éclater avec $formateur ;)";
        // $title = "Bienvenue sur la page de test !";
        $products = $productRepository->findAll();
        // dd($products);
       

        return $this->render('test/home.html.twig', [
            'products' => $products,
            // 'title' => $title
        ]);
        // return new Response ("<h1>Fonction test</h1>");
    }


     /**
     * @Route("/success", name="success")
     */
    public function success(): Response
    {
      return $this->render('success.html.twig', []);
    }
}
