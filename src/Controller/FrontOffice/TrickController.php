<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;

class TrickController extends AbstractController
{
    /**
     * @Route("/snow-tricks/accueil", name="homepage", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository)
    {
        $listTricks = $trickRepository->getTricks();
        return $this->render('/frontoffice/home.html.twig', array('listTricks' => $listTricks));
    }
}



