<?php

namespace App\Controller\FrontOffice;

use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name="user_registration")
     */
    public function register()
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        return $this->renderForm('/frontoffice/registration.html.twig', array('form' => $form));
    }
}