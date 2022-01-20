<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\RegisterFormType;
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
     * @Route("/registration", name="app_registration")
     */
    public function register(
        Request                     $request,
        ManagerRegistry             $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        RoleRepository              $roleRepository,
        Mailer                      $mailer,
        TokenGeneratorInterface     $tokenGenerator,
        UserRepository              $userRepository
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $existing_user = $userRepository->findOneBy(array('email' => $email));
            if (!$existing_user) {
                $password = $form->get('password')->getData();
                $user->setPassword($passwordHasher->hashPassword($user, $password));
                $role = $roleRepository->find('1');
                $user->addRole($role);
                $user->setRoles((array)$role->getRoleName());
                $user->setToken($tokenGenerator->generateToken());
                $user->setProfileStatus(false);

                $em = $doctrine->getManager();
                $em->persist($user);
                $em->flush();

                $email = $user->getEmail();
                $username = $user->getUserIdentifier();
                $token = $user->getToken();
                $mailer->sendEmail($email, $username, $token);

                $this->addFlash(
                    'success',
                    'Votre compte a été créé avec succès, un mail d\'activation vous a été envoyé à l\'adresse : ' . $email
                );
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('existingUser', 'Un compte existe déjà avec cette adresse email !!');
                return $this->redirectToRoute('app_registration');
            }
        }
        return $this->renderForm('/frontoffice/registration.html.twig', array('form' => $form));
    }

    /**
     * @Route("/confirm_account/{token}", name="app_account_confirmation")
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

            return $this->redirectToRoute('app_homepage');
        } else {

            return $this->redirectToRoute('app_homepage');
        }
    }
}