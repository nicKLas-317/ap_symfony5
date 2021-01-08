<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session): Response
    {

        $cart  = $session->get('cart', []);
      
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

     /**
     * @Route("/cart/add/{id}", name="addToCart")
     */
    public function add(Request $request, SessionInterface $session, $id): Response
    {

        $cart  = $session->get('cart', []);

        array_push($cart, $id);
        $session->set('cart', $cart);

        $this->addFlash('success', 'Produit ajouté au panier');

        return $this->render('home.html.twig');
    }
}
