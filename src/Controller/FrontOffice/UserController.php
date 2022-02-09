<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\RegisterType;
use App\Form\ResetPasswordType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    private $em;
    private $roleRepository;
    private $userRepository;
    private $tokenGenerator;
    private $passwordHasher;
    private $mailer;

    public function __construct(
        EntityManagerInterface      $em,
        RoleRepository              $roleRepository,
        UserRepository              $userRepository,
        TokenGeneratorInterface     $tokenGenerator,
        UserPasswordHasherInterface $passwordHasher,
        Mailer                      $mailer
    )
    {
        $this->em = $em;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/registration", name="app_registration")
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $existing_user = $this->userRepository->findOneBy(['email' => $email]);

            if (!$existing_user) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('password')->getData()));

                $role = $this->roleRepository->find('1');
                $user->addRole($role);
                $user->setRoles((array)$role->getRoleName());
                $user->setToken($this->tokenGenerator->generateToken());
                $user->setProfileStatus(false);

                $this->em->persist($user);
                $this->em->flush();

                $email = $user->getEmail();
                $username = $user->getUserIdentifier();
                $token = $user->getToken();
                $subject = 'activer votre compte SnowTricks';
                $htmlTemplate = '/emails/activation.html.twig';
                $this->mailer->sendEmail($email, $username, $token, $subject, $htmlTemplate);

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

        return $this->renderForm('/frontoffice/registration.html.twig', ['form' => $form]);
    }

    /**
     * @Route("/confirm_account/{token}", name="app_account_confirmation")
     *
     * @param $token
     * @return RedirectResponse
     */
    public function confirmAccount($token): redirectResponse
    {
        $user = $this->userRepository->findOneBy(['token' => $token]);

        if ($user) {
            $user->setToken(null);
            $user->setEnabled(true);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('app_login');
        } else {
            return $this->redirectToRoute('app_homepage');
        }
    }

    /**
     * @Route("/forgot_password", name="app_forgot_password")
     *
     * @param Request $request
     * @return Response
     */
    public function forgotPassword(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $user = $this->userRepository->findOneBy(['username' => $username]);

            if (!$user) {
                $this->addFlash('existingUser', 'Aucun compte existant avec ce nom d\'utilisateur');
                return $this->redirectToRoute('app_forgot_password');
            }

            $email = $user->getEmail();
            $token = $user->getToken();
            $subject = 'Réinitialiser votre mot de passe';
            $htmlTemplate = '/emails/forgotPassword.html.twig';
            $this->mailer->sendEmail($email, $username, $token, $subject, $htmlTemplate);

            $this->addFlash('resetPasswordRequestSuccess', 'Un mail vous a été envoyé à l\'adresse : ' . $email);
            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->renderForm('frontoffice/forgotPassword.html.twig', ['form' => $form]);
    }

    /**
     * @Route("reset_password/{username}", name="app_reset_password")
     *
     * @param Request $request
     * @param string $username
     * @return Response
     */
    public function resetPassword(Request $request, string $username): Response
    {
        $user = new User();
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy(['username' => $username]);

            if (!$user) {
                throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
            }

            $password = $form->get('password')->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));

            $this->em->flush();
            $this->addFlash('resetPasswordSuccess', 'Votre mon de passe a été modifié avec succès');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->renderForm('frontoffice/resetPassword.html.twig', ['form' => $form, 'username' => $username]);
    }
}
