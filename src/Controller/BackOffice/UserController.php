<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\CreateUserType;
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

/**
 * @Route("/admin/users", name="admin_")
 *
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="users_list")
     */
    public function usersList(UserRepository $userRepository)
    {
        $users = $userRepository->getUsers();
        return $this->render('/backoffice/usersList.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/details/{slug}", name="user_details")
     */
    public function show(userRepository $userRepository, Request $request): Response
    {
        $slug = $request->get('slug');
        $userDetails = $userRepository->getUser($slug);
        return $this->render(
            '/backoffice/userDetails.html.twig', [
            'userDetails' => $userDetails
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function delete(UserRepository $userRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $user_id = $request->get('id');
        $userDelete = $userRepository->find($user_id);
        $em = $doctrine->getManager();
        $em->remove($userDelete);
        $em->flush();
        return $this->redirectToRoute('admin_users_list');
    }

    /**
     * @Route("/create", name="user_create")
     */
    public function new(
        Request                     $request,
        UserRepository              $userRepository,
        RoleRepository              $roleRepository,
        ManagerRegistry             $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        TokenGeneratorInterface     $tokenGenerator,
        Mailer                      $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(CreateUserType::class, $user);
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
                $subject = 'activer votre compte SnowTricks';
                $htmlTemplate = '/emails/activation.html.twig';
                $mailer->sendEmail($email, $username, $token, $subject, $htmlTemplate);

                return $this->redirectToRoute('admin_users_list');
            } else {
                $this->addFlash('existingUser', 'Un compte existe déjà avec cette adresse email !!');
                return $this->redirectToRoute('admin_user_create');
            }
        }
        return $this->renderForm('/backoffice/userCreate.html.twig', ['form' => $form]);
    }
}