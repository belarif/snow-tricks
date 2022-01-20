<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\RegisterFormType;
use App\Form\ResetPasswordFormTymeType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name="user_registration")
     */
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher, RoleRepository $roleRepository, Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $role = $roleRepository->find('1');
            $user->addRole($role);
            $user->setRoles((array)$role->getRoleName());
            $user->setToken($tokenGenerator->generateToken());

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $email = $user->getEmail();
            $username = $user->getUserIdentifier();
            $token = $user->getToken();
            $subject = 'activer votre compte SnowTricks';
            $htmlTemplate = '/emails/activation.html.twig';
            $mailer->sendEmail($email, $username, $token, $subject, $htmlTemplate);

            $this->addFlash(
                'success',
                'Votre compte a été créé avec succès, un mail d\'activation vous a été envoyé à l\'adresse : ' . $email
            );
            return $this->redirectToRoute('user_registration');
        }
        return $this->renderForm('/frontoffice/registration.html.twig', array('form' => $form));
    }

    /**
     * @Route("/confirm_account/{token}", name="user_account_confirmation")
     */
    public function confirmAccount($token, UserRepository $userRepository, ManagerRegistry $doctrine)
    {
        $user = $userRepository->findOneBy(['token' => $token]);
        if ($user) {
            $user->setToken(null);
            $user->setEnabled(true);
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('home_page');
        } else {

            return $this->redirectToRoute('home_page');
        }
    }

    /**
     * @Route("/forgot_password", name="app_forgot_password")
     */
    public function forgotPassword(Request $request, UserRepository $userRepository, Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(ForgotPasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $user = $userRepository->findOneBy(array('username' => $username));
            if (!$user) {
                $this->addFlash(
                    'existingUser',
                    'Aucun compte existant avec cette adresse email'
                );
                return $this->redirectToRoute('app_forgot_password');
            }

            $email = $user->getEmail();
            $token = $user->getToken();
            $subject = 'Réinitialiser votre mot de passe';
            $htmlTemplate = '/emails/forgotPassword.html.twig';
            $mailer->sendEmail($email, $username, $token, $subject, $htmlTemplate);
        }

        return $this->renderForm('frontoffice/forgotPassword.html.twig', ['form' => $form]);
    }

    /**
     * @Route("reset_password", name="app_reset_password")
     */
    public function resetPassword(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(ResetPasswordFormTymeType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();
            $user->setUsername($username);
            $user->setPassword($passwordHasher->hashPassword($user, $password));

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_homepage');
        }
        return $this->renderForm('frontoffice/resetPassword.html.twig', ['form' => $form]);
    }
}