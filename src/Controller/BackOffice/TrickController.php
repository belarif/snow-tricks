<?php

namespace App\Controller\BackOffice;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
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
     * @Route("/details/{slug}", name="trick_details")
     */
    public function show(TrickRepository $trickRepository, Request $request): Response
    {
        $slug = $request->get('slug');
        $trickDetails = $trickRepository->getTrick($slug);
        return $this->render('/backoffice/trickDetails.html.twig', array('trickDetails' => $trickDetails));
    }

    /**
     * @Route("/delete/{id}", name="trick_delete")
     */
    public function delete(TrickRepository $trickRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $trick_id = $request->get('id');
        $trickDelete = $trickRepository->find($trick_id);
        $em = $doctrine->getManager();
        $em->remove($trickDelete);
        $em->flush();
        return $this->redirectToRoute('admin_tricks_list');
    }
}


