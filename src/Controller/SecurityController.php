<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


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
