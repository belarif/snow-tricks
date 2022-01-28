<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\CreateUserType;
use App\Form\EditUserType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    private $userRepository;
    private $managerRegistry;
    private $roleRepository;
    private $passwordHasher;
    private $tokenGenerator;
    private $mailer;

    public function __construct(
        UserRepository              $userRepository,
        ManagerRegistry             $managerRegistry,
        RoleRepository              $roleRepository,
        UserPasswordHasherInterface $passwordHasher,
        TokenGeneratorInterface     $tokenGenerator,
        Mailer                      $mailer
    )
    {
        $this->userRepository = $userRepository;
        $this->managerRegistry = $managerRegistry;
        $this->roleRepository = $roleRepository;
        $this->passwordHasher = $passwordHasher;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/list", name="users_list", methods={"GET"})
     * @return Response
     */
    public function usersList(): Response
    {
        $users = $this->userRepository->getUsers();
        return $this->render('/backoffice/usersList.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/details/{id}/{slug?}", name="user_details", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $id = $request->get('id');
        $userDetails = $this->userRepository->getUser($id);
        return $this->render('/backoffice/userDetails.html.twig', ['userDetails' => $userDetails]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): redirectResponse
    {
        $user_id = $request->get('id');
        $userDelete = $this->userRepository->find($user_id);
        $em = $this->managerRegistry->getManager();
        $em->remove($userDelete);
        $em->flush();
        return $this->redirectToRoute('admin_users_list');
    }

    /**
     * @Route("/create", name="user_create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $existing_user = $this->userRepository->findOneBy(['email' => $email]);
            if (!$existing_user) {
                $this->processCreation($form, $user);

                $email = $user->getEmail();
                $username = $user->getUserIdentifier();
                $token = $user->getToken();
                $subject = 'activer votre compte SnowTricks';
                $htmlTemplate = '/emails/activation.html.twig';
                $this->mailer->sendEmail($email, $username, $token, $subject, $htmlTemplate);

                return $this->redirectToRoute('admin_users_list');
            } else {
                $this->addFlash('existingUser', 'Un compte existe déjà avec cette adresse email !!');
                return $this->redirectToRoute('admin_user_create');
            }
        }
        return $this->renderForm('/backoffice/userCreate.html.twig', ['form' => $form]);
    }

    /**
     * @param $form
     * @param $user
     */
    private function processCreation($form, $user): void
    {
        $password = $form->get('password')->getData();
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $role = $this->roleRepository->find('1');
        $user->addRole($role);
        $user->setRoles((array)$role->getRoleName());
        $user->setToken($this->tokenGenerator->generateToken());
        $user->setProfileStatus(false);
        $em = $this->managerRegistry->getManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @Route("/edit/{id}/{slug?}", name="user_edit", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $request->get('id');
            $this->update($id, $form);
            return $this->redirectToRoute('admin_users_list');
        }
        return $this->renderForm('/backoffice/userEdit.html.twig', ['form' => $form]);
    }

    /**
     * @param $form
     * @param $id
     */
    private function update($id, $form): void
    {
        $selectedUser = $this->userRepository->findOneBy(['id' => $id]);
        if (!$selectedUser) {
            throw $this->createNotFoundException('Utilisateur inexistant');
        }
        $selectedUser->setEnabled($form->get('enabled')->getData());
        $em = $this->managerRegistry->getManager();
        $em->persist($selectedUser);
        $em->flush();
    }
}