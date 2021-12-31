<?php

namespace App\Controller\BackOffice;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tricks", name="admin_")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/list", name="tricks_list")
     */
    public function tricksList(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->getTricks();

        return $this->render('/backoffice/tricksList.html.twig', array('tricks' => $tricks));
    }

    /**
     * @Route("/details/{id}", name="trick_details")
     */
    public function show(TrickRepository $trickRepository, Request $request): Response
    {
        $trick_id = $request->get('id');
        $trickDetails = $trickRepository->getTrick($trick_id);
        return $this->render('/backoffice/trickDetails.html.twig', array('trickDetails' => $trickDetails));
    }

    /**
     * @Route("/delecte/{id}", name="trick_delete")
     */
}

