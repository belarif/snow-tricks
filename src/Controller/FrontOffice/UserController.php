<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\RoleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name="user_registration")
     */
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, RoleRepository $roleRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            $email = $form->get('email')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $password));

            $role = $roleRepository->find('1');
            $user->addRole($role);

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès, un mail de validation vous a été envoyé à l\'adresse : ' . $email);
            return $this->redirectToRoute('user_registration');
        }
        return $this->renderForm('/frontoffice/registration.html.twig', array('form' => $form));
    }
}