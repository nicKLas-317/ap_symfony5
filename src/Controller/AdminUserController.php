<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
    * @Route("/profile/userlist", name="userList")
    */
    public function userList(UserRepository $userRepository)
    {
        // get all users
        $users =  $userRepository->findAll();
        // display users
        return $this->render('security/allUsers.html.twig', [
            'users' => $users
        ]);
    }


      /**
     * @Route("/profile/user/add", name="ajoutUser")
     */
    public function addUser(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        // REstrictions admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User;

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Le user a bien été créé ;)');

            return $this->redirectToRoute('userList');
        }

        return $this->render('security/addUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/user/edit/{id}", name="editUser")
     */
    public function editUser(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, Request $request, $id)
    {

        //    Get user with id
        $user = $em->getRepository(User::class)->find($id);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Modifications bien enregistrées ;)');
            return $this->redirectToRoute('userList');
        }

        return $this->render('security/editUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/profile/user/delete/{id}", name="deleteUser")
     */
    public function deleteUser(EntityManagerInterface $em, $id)
    {
         // REstrictions admin
         $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $em->getRepository(User::class)->find($id);

        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Le user a bien été supprimé ;)');


        return $this->redirectToRoute('userList');
    }

}
