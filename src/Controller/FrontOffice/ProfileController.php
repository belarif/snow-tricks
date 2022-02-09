<?php

namespace App\Controller\FrontOffice;

use App\Form\EditProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    private $em;

    public function __construct(
        UserRepository         $userRepository,
        EntityManagerInterface $em
    )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
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
        $user = $this->userRepository->findOneBy(['username' => $username]);

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->update($form, $user);

            $this->addFlash('success', 'Votre inscription a été complété avec succès');
            $this->redirectToRoute('app_profile');
        }

        return $this->renderForm('/frontoffice/profile.html.twig', ['form' => $form]);
    }

    /**
     * @param $form
     * @param $user
     */
    private function update($form, $user): void
    {
        $avatar = $form->get('avatar')->getData();
        $file = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $avatar->guessExtension();
        $avatar->move($this->getParameter('app.avatars_directory'), $file);

        $user->setAvatar($file);
        $user->setProfileStatus(true);
        $user->setSlug(preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($form->get('lastName')->getData()))));

        $this->em->flush();
    }
}

