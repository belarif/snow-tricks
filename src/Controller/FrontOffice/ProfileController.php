<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\EditProfileType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_VISITOR")
 */
class ProfileController extends AbstractController
{

    private $userRepository;
    private $managerRegistry;

    public function __construct(
        UserRepository  $userRepository,
        ManagerRegistry $managerRegistry
    )
    {
        $this->userRepository = $userRepository;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/profile", name="app_profile", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request): Response
    {
        $loggedUser = $this->getUser();
        if (!$loggedUser) {
            throw $this->createNotFoundException('Utilisateur inexistant');
        }
        $username = $loggedUser->getUserIdentifier();
        $loggedUser = $this->userRepository->findOneBy(['username' => $username]);

        $user = new User();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->update($form, $loggedUser);
            $this->addFlash(
                'success',
                'Votre inscription a été complété avec succès'
            );
            $this->redirectToRoute('app_profile');
        }
        return $this->renderForm('/frontoffice/profile.html.twig', ['form' => $form]);
    }

    /**
     * @param $form
     * @param $loggedUser
     */
    private function update($form, $loggedUser): void
    {
        $lastName = $form->get('lastName')->getData();
        $firstName = $form->get('firstName')->getData();
        $loggedUser->setLastName($lastName);
        $loggedUser->setFirstName($firstName);
        $loggedUser->setSlug($lastName, $firstName);
        $avatar = $form->get('avatar')->getData();
        $file = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $avatar->guessExtension();
        $avatar->move(
            $this->getParameter('app.avatars_directory'),
            $file
        );
        $loggedUser->setAvatar($file);
        $loggedUser->setProfileStatus(true);
        $loggedUser->setSlug(preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($lastName))));

        $em = $this->managerRegistry->getManager();
        $em->persist($loggedUser);
        $em->flush();
    }
}