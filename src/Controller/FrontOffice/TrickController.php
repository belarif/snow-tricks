<?php

namespace App\Controller\FrontOffice;

use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CreateTrickType;
use App\Form\EditTrickType;
use App\Form\ImageType;
use App\Form\MessageTrickType;
use App\Form\TrickType;
use App\Form\VideoType;
use App\Repository\TrickRepository;
use App\Service\MediaUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/tricks", name="trick_")
 */
class TrickController extends AbstractController
{
    private $em;

    private $trickRepository;

    public function __construct(
        EntityManagerInterface $em,
        TrickRepository $trickRepository
    ) {
        $this->em = $em;
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, MediaUploader $uploader, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createTrickProcess($form, $trick, $uploader);

            $trick->setSlug($slugger->slug($trick->getName()));
            $trick->setUser($this->getUser());

            $this->em->persist($trick);
            $this->em->flush();

            $this->addFlash('successCreateTrick', 'Le trick a été créé avec succès');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->renderForm('/frontoffice/createTrick.html.twig', [
            'form' => $form,
            'trick' => $trick
        ]);
    }


    /**
     * @Route("/details/{id}/{slug}", name="details", methods={"GET","POST"},
     *     requirements={"id"="\d+", "slug"="[-a-z0-9]+"})
     *
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public function show(Request $request, Trick $trick): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageTrickType::class, $message);
        $form->handleRequest($request);

        $id = $trick->getId('id');

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addTrickMessage($trick, $form, $message);
            $this->addFlash('messageSentSuccess', 'Votre message a été envoyé avec succès');
            return $this->redirectToRoute('trick_details', [
                'id' => $id,
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->renderForm('/frontoffice/detailsTrick.html.twig', [
            'trick' => $trick,
            'form' => $form
        ]);
    }

    /**
     * @param Trick $trick
     * @param FormInterface $form
     * @param Message $message
     */
    private function addTrickMessage(Trick $trick, FormInterface $form, Message $message): void
    {
        $trick = $this->trickRepository->findOneBy(['id' => $trick->getId()]);
        $message->setUser($this->getUser());
        $message->setTrick($trick);
        $message->setContent($form->get('content')->getData());

        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * @Route("/edit/{id}/{slug}", name="edit", methods={"GET","POST"},
     *     requirements={"id"="\d+", "slug"="[-a-z0-9]+"})
     *
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public function edit(Request $request, Trick $trick, MediaUploader $uploader): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        $video = new Video();
        $video->setTrick($trick);
        $formVideo = $this->createForm(VideoType::class, $video);

        $image = new Image();
        $image->setTrick($trick);
        $formImage = $this->createForm(ImageType::class, $image);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->createTrickProcess($form, $trick, $uploader);

            $this->em->flush();
            $this->addFlash('trickEditSuccess', 'Le trick a été modifié avec succès');
            return $this->redirectToRoute('trick_edit', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->renderForm('/frontoffice/editTrick.html.twig', [
            'trick' => $trick,
            'form' => $form,
            'form_video' => $formVideo,
            'form_image' => $formImage
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete",
     *     requirements={"id"="\d+"})
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id, MediaUploader $uploader): redirectResponse
    {
        $trick = $this->trickRepository->find($id);
        foreach ($trick->getImages() as $image) {
            $uploader->removeImage($image);
        }

        $this->em->remove($trick);
        $this->em->flush();

        $this->addFlash('successDeleteTrick', 'Le trick a été supprimé avec succès');
        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @param FormInterface $form
     * @param Trick $trick
     * @param MediaUploader $uploader
     */
    private function createTrickProcess(FormInterface $form, Trick $trick, MediaUploader $uploader): void
    {
        $images = $form->get('images')->getData();
        foreach ($images as $image) {
            $fileName = $uploader->upload($image);
            $image = new Image();
            $image->setSrc($fileName);
            $trick->addImage($image);
        }

        $trick->addVideosFromArray($form->get('videos')->getData());
    }
}
