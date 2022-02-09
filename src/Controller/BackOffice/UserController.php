<?php

namespace App\Controller\BackOffice;

use App\Entity\User;
use App\Form\CreateUserType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/admin/users", name="admin_")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    private $userRepository;

    private $em;

    private $passwordHasher;

    private $tokenGenerator;

    private $mailer;

    public function __construct(
        UserRepository              $userRepository,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $passwordHasher,
        TokenGeneratorInterface     $tokenGenerator,
        Mailer                      $mailer
    )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/list", name="users_list", methods={"GET"})
     *
     * @return Response
     */
    public function usersList(): Response
    {
        return $this->render('/backoffice/usersList.html.twig', ['users' => $this->userRepository->getUsers()]);
    }

    /**
     * @Route("/details/{id}/{slug?}", name="user_details", methods={"GET"})
     *
     * @param int $id
     * @return Response
     * @throws EntityNotFoundException
     */
    public function show(int $id): Response
    {
        return $this->render('/backoffice/userDetails.html.twig', ['user' => $this->userRepository->getUser($id)]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): redirectResponse
    {
        $this->em->remove($this->userRepository->find($id));
        $this->em->flush();

        return $this->redirectToRoute('admin_users_list');
    }

    /**
     * @Route("/create", name="user_create", methods={"GET","POST"})
     *
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
        $user->setToken($this->tokenGenerator->generateToken());
        $user->setProfileStatus(false);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @Route("/edit/{id}/{slug?}", name="user_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $user = $this->userRepository->getUser($id);
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->renderForm('/backoffice/userEdit.html.twig', ['form' => $form, 'user' => $user]);
    }
}
