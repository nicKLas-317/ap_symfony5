<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        // $session = new Session();
        // $session->clear();
        $cart  = $session->get('cart', []);

        $cart_view = array_count_values($cart);
      
        foreach ($cart_view as $key => $value) {
            $newCart[] = [
                'product' => $productRepository->find($key),
                'quantity'=> $value
            ];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $newCart,
        ]);
    }
    

    /**
     * @Route("/cart/add/{id}", name="addToCart")
     */
    public function add(Request $request, SessionInterface $session, $id, ProductRepository $productRepository): Response
    {

        $cart  = $session->get('cart', []);

        $arrayCart = (array) $cart;
        array_push($arrayCart, $id);
        $session->set('cart', $arrayCart);

        $this->addFlash('success', 'Produit ajouté au panier');
        $products = $productRepository->findAll();
        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
}
