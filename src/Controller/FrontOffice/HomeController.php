<?php

namespace App\Controller\FrontOffice;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/accueil", name="home_page")
     */
    public function index(TrickRepository $trickRepository)
    {
        $listTricks = $trickRepository->getTricks();
        return $this->render('/frontoffice/home.html.twig', array('listTricks' => $listTricks));
    }
}



