<?php

namespace App\Controller\FrontOffice;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('/frontoffice/login.html.twig', [
            'controller_name' => 'SecurityController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws Exception
     */
    public function logout(): void
    {
        throw new Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/accessDenied", name="app_access_denied")
     *
     * @return Response
     */
    public function accessDeniedHandler(): Response
    {
        $response = 'L\'accès à la page demandée est limité !!';
        return $this->render('/frontoffice/accessDenied.html.twig', ['response' => $response]);
    }
}



