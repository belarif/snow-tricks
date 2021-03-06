<?php

namespace App\Controller\FrontOffice;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_homepage", methods={"GET"})
     *
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function home(TrickRepository $trickRepository): Response
    {
        return $this->render('/frontoffice/home.html.twig', [
            'tricks' => $trickRepository->getTricks()
        ]);
    }
}
