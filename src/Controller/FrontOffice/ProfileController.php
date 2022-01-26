<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\ProfileFormType;
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

    private $doctrine;

    public function __construct(UserRepository $userRepository, ManagerRegistry $doctrine)
    {
        $this->userRepository = $userRepository;
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(Request $request): Response
    {
        $logedUser = $this->getUser();
        if (!$logedUser) {
            throw $this->createNotFoundException('Utilisateur inexistant');
        }
        $username = $logedUser->getUserIdentifier();
        $logedUser = $this->userRepository->findOneBy(array('username' => $username));

        $user = new User();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->update($form, $logedUser);

            $this->addFlash(
                'success',
                'Votre inscription a été complété avec succès'
            );
            $this->redirectToRoute('app_profile');
        }

        return $this->renderForm('/frontoffice/profile.html.twig', array('form' => $form));
    }

    private function update($form, $logedUser)
    {
        $lastName = $form->get('lastName')->getData();
        $firstName = $form->get('firstName')->getData();
        $logedUser->setLastName($lastName);
        $logedUser->setFirstName($firstName);
        $logedUser->setSlug($lastName, $firstName);
        $avatar = $form->get('avatar')->getData();
        $file = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $avatar->guessExtension();
        $avatar->move(
            $this->getParameter('app.avatars_directory'),
            $file
        );
        $logedUser->setAvatar($file);
        $logedUser->setProfileStatus(true);
        $logedUser->setSlug(preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($lastName))));

        $em = $this->doctrine->getManager();
        $em->persist($logedUser);
        $em->flush();
    }
}