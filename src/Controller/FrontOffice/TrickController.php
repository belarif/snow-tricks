<?php

namespace App\Controller\FrontOffice;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tricks", name="trick_")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function add(): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        return $this->renderForm('/frontoffice/add_trick.html.twig', array('form' => $form, 'trick' => $trick));
    }

    /**
     * @Route("/details/{slug}", name="details")
     */
    public function show(TrickRepository $trickRepository, Request $request): Response
    {
        $slug = $request->get('slug');
        $trickDetails = $trickRepository->getTrick($slug);
        return $this->render('/frontoffice/trick_details.html.twig', array('trickDetails' => $trickDetails));
    }

    /**
     * @Route("/edit/{slug}", name="edit")
     */
    public function edit(Request $request, TrickRepository $trickRepository): Response
    {
        $slug = $request->get('slug');
        $editTrick = $trickRepository->getTrick($slug);
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        return $this->renderForm('/frontoffice/edit_trick.html.twig', array('editTrick' => $editTrick, 'form' => $form));
    }
}