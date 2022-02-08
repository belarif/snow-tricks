<?php

namespace App\Controller\FrontOffice;

use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Trick;
use App\Form\CreateTrickType;
use App\Form\EditTrickType;
use App\Form\MessageTrickType;
use App\Repository\GroupRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tricks", name="trick_")
 */
class TrickController extends AbstractController
{
	private EntityManagerInterface $em;

    private TrickRepository $trickRepository;

    private GroupRepository $groupRepository;

    public function __construct(
		EntityManagerInterface $em,
        TrickRepository $trickRepository,
        GroupRepository $groupRepository
    )
    {
		$this->em = $em;
        $this->trickRepository = $trickRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     * @IsGranted("ROLE_VISITOR")
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(CreateTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createTrickProcess($form, $trick);
            $this->addFlash('successCreateTrick', 'Le trick a été créé avec succès');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->renderForm('/frontoffice/createTrick.html.twig', ['form' => $form, 'trick' => $trick]);
    }

	/**
	 * @param FormInterface $form
	 * @param Trick $trick
	 */
    private function createTrickProcess(FormInterface $form, Trick $trick)
    {
	    $name = $form->get('name')->getData();
        $images = $form->get('images')->getData();

        foreach ($images as $img) {
            $file = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $img->guessExtension();
            $img->move($this->getParameter('app.images_directory'), $file);

            $image = new Image();
            $image->setSrc($file);
            $trick->addImage($image);
        }

		$trick->addVideosFromArray($form->get('videos')->getData());
        $trick->setSlug(preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($name))));
        $trick->setUser($this->getUser());

        $this->em->persist($trick);
        $this->em->flush();
    }

	/**
	 * @Route("/details/{id}/{slug}", name="details", methods={"GET","POST"})
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 * @throws \Doctrine\ORM\EntityNotFoundException
	 */
    public function show(Request $request, int $id): Response
    {
        $trick = $this->trickRepository->getTrick($id);

        $message = new Message();
        $form = $this->createForm(MessageTrickType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageTrick($id, $form, $message);
            $this->addFlash('messageSentSuccess', 'Votre message a été envoyé avec succès');
            return $this->redirectToRoute('trick_details', ['id' => $id, 'slug' => $trick->getSlug()]);
        }

        return $this->renderForm('/frontoffice/detailsTrick.html.twig', [
            'trick' => $trick,
            'form' => $form
        ]);
    }

    /**
     * @param int|string $id
     * @param FormInterface $form
     * @param Message $message
     */
    private function messageTrick($id, FormInterface $form, Message $message): void
    {
	    $trick = $this->trickRepository->findOneBy(['id' => $id]);
        $message->setUser($this->getUser());
        $message->setTrick($trick);
        $message->setContent($form->get('content')->getData());

        $this->em->persist($message);
        $this->em->flush();
    }

	/**
	 * @Route("/edit/{id}/{slug}", name="edit", methods={"GET","POST"})
	 * @IsGranted("ROLE_VISITOR")
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 * @throws \Doctrine\ORM\EntityNotFoundException
	 */
    public function edit(Request $request, int $id): Response
    {
        $trick = $this->trickRepository->getTrick($id);

        $form = $this->createForm(EditTrickType::class, $trick);
        $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->flush();

			$this->addFlash('trickEditSuccess', 'Le trick a été modifié avec succès');
			$this->redirectToRoute('trick_edit', ['id' => $id, 'slug' => $trick->getSlug()]);
        }

        return $this->renderForm('/frontoffice/editTrick.html.twig', [
            'trick' => $trick,
            'form' => $form
        ]);
    }

	/**
	 * @Route("/delete/{id}", name="delete")
	 * @IsGranted("ROLE_VISITOR")
	 *
	 * @param Request $request
	 * @param int $id
	 * @return RedirectResponse
	 */
    public function delete(Request $request, int $id): redirectResponse
    {
        $trickDelete = $this->trickRepository->find($id);
        $this->em->remove($trickDelete);
        $this->em->flush();

        $this->addFlash('successDeleteTrick', 'Le trick a été supprimé avec succès');
        return $this->redirectToRoute('app_homepage');
    }
}
