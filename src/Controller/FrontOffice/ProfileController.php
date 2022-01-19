<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(UserRepository $userRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
        }
        return $this->renderForm('/frontoffice/profile.html.twig', array('form' => $form));
    }
}