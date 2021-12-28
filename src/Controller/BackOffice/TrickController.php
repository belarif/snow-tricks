<?php

namespace App\Controller\BackOffice;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard", name="admin_")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/tricks_list", name="tricks_list")
     */
    public function tricksList(TrickRepository $trickRepository)
    {
        $tricks = $trickRepository->getTricks();

        return $this->render('/backoffice/tricksList.html.twig', array('tricks' => $tricks));
    }
}
