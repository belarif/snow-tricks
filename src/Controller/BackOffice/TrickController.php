<?php

namespace App\Controller\BackOffice;

use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tricks", name="admin_")
 */
class TrickController extends AbstractController
{
    private $trickRepository;

    private $em;

    public function __construct(EntityManagerInterface $em, TrickRepository $trickRepository)
    {
        $this->em = $em;
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/list", name="tricks_list", methods={"GET"})
     *
     * @return Response
     */
    public function tricksList(): Response
    {
        return $this->render('/backoffice/tricksList.html.twig', ['tricks' => $this->trickRepository->getTricks()]);
    }

    /**
     * @Route("/details/{id}/{slug}", name="trick_details", methods={"GET"})
     *
     * @param int $id
     * @return Response
     * @throws EntityNotFoundException
     */
    public function show(int $id): Response
    {
        return $this->render('/backoffice/trickDetails.html.twig', ['trick' => $this->trickRepository->getTrick($id)]);
    }

    /**
     * @Route("/delete/{id}", name="trick_delete")
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $this->em->remove($this->trickRepository->find($id));
        $this->em->flush();

        return $this->redirectToRoute('admin_tricks_list');
    }
}





