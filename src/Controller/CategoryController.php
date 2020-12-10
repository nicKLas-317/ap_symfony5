<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;

use App\Form\CategoryFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $listeCategory = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'listeCategory' => $listeCategory,
        ]);
    }

    /**
     * @Route("category/add", name= "ajoutCategorie")
     *
     */
    public function addCategory(EntityManagerInterface $em, Request $request)
    {
        $category = new Category;
        $form=  $this->createForm(CategoryFormType::class, $category);
       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            // $message = $this->addFlash('success', 'Bien enregistré ;)');
            return $this->redirectToRoute('categories');
        }
        
        return $this->render('category/add.html.twig', ['form' => $form->createView()
        // , 'message' => $message
        ]);
    }


     /**
     * @Route("category/delete/{id}", name= "deleteCategorie")
     *
     */
    public function deleteCategory(EntityManagerInterface $em, CategoryRepository $categoryRepository,  $id)
    {
        $category = $em->getRepository(Category::class)->find($id);
  
        $em->remove($category);
        $em->flush();
        $listeCategory = $categoryRepository->findAll();
    // $message = $this->addFlash('success', 'Bien enregistré ;)');
        return $this->render('category/index.html.twig', [
            'listeCategory' => $listeCategory,
        ]);
      
    }


    /**
     * @Route("category/edit/{id}", name= "editCategorie")
     *
     */
    public function editCategory(EntityManagerInterface $em, Request $request, $id)
    {
    //    Get category with id
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);
       
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            // $message = $this->addFlash('success', 'Bien enregistré ;)');
            // var_dump($message);
            return $this->redirectToRoute('categories');
        }
        
        return $this->render('category/edit.html.twig', ['form' => $form->createView()
        // ,'message' => $message
        ]);
    }


  
}
