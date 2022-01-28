<?php

namespace App\Controller\BackOffice;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tricks", name="admin_")
 *
 */
class TrickController extends AbstractController
{
    private $trickRepository;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry, TrickRepository $trickRepository)
    {
        $this->managerRegistry = $managerRegistry;
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/list", name="tricks_list", methods={"GET"})
     * @return Response
     */
    public function tricksList(): Response
    {
        $tricks = $this->trickRepository->getTricks();
        return $this->render('/backoffice/tricksList.html.twig', ['tricks' => $tricks]);
    }

    /**
     * @Route("/details/{id}/{slug}", name="trick_details", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $id = $request->get('id');
        $trickDetails = $this->trickRepository->getTrick($id);
        return $this->render('/backoffice/trickDetails.html.twig', ['trickDetails' => $trickDetails]);
    }

    /**
     * @Route("/delete/{id}", name="trick_delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $trick_id = $request->get('id');
        $trickDelete = $this->trickRepository->find($trick_id);
        $em = $this->managerRegistry->getManager();
        $em->remove($trickDelete);
        $em->flush();
        return $this->redirectToRoute('admin_tricks_list');
    }
}



