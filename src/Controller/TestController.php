<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/test", name="test")
     */
    public function test(): Response
    {
        $formateur = "Yvon";
        $val = "Hello et bienvenue sur la super page de tests, on va s'éclater avec $formateur ;)";
        $title = "Bienvenue sur la page de test !";

        return $this->render('test/test.html.twig', [
            'val' => $val,
            'title' => $title
        ]);
        // return new Response ("<h1>Fonction test</h1>");
    }
}
