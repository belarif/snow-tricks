<?php

namespace App\Controller\FrontOffice;

use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CreateTrickType;
use App\Form\EditTrickType;
use App\Form\MessageTrickType;
use App\Repository\GroupRepository;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tricks", name="trick_")
 */
class TrickController extends AbstractController
{
    private $trickRepository;
    private $managerRegistry;
    private $userRepository;
    private $imageRepository;
    private $videoRepository;
    private $groupRepository;

    public function __construct(
        ManagerRegistry $managerRegistry,
        TrickRepository $trickRepository,
        UserRepository  $userRepository,
        ImageRepository $imageRepository,
        VideoRepository $videoRepository,
        GroupRepository $groupRepository
    )
    {
        $this->managerRegistry = $managerRegistry;
        $this->trickRepository = $trickRepository;
        $this->userRepository = $userRepository;
        $this->imageRepository = $imageRepository;
        $this->videoRepository = $videoRepository;
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
     * @param $form
     * @param $trick
     */
    private function createTrickProcess($form, $trick)
    {
        $images = $form->get('images')->getData();
        foreach ($images as $img) {
            $file = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $img->guessExtension();
            $img->move($this->getParameter('app.images_directory'), $file);
            $image = new Image();
            $image->setSrc($file);
            $trick->addImage($image);
        }
        $videos = $form->get('videos')->getData();
        foreach ($videos as $src) {
            $video = new Video();
            $video->setSrc($src);
            $trick->addVideo($video);
        }

        $username = $this->getUser()->getUserIdentifier();
        $user = $this->userRepository->findOneBy(['username' => $username]);
        $name = $form->get('name')->getData();
        $slug = preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($name)));
        $trick->setSlug($slug);
        $trick->setUser($user);

        $em = $this->managerRegistry->getManager();
        $em->persist($trick);
        $em->flush();
    }

    /**
     * @Route("/details/{id}/{slug}", name="details", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $id = $request->get('id');
        $slug = $request->get('slug');
        $trickDetails = $this->trickRepository->getTrick($id);

        $message = new Message();
        $form = $this->createForm(MessageTrickType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageTrick($id, $form, $message);
            $this->addFlash('messageSentSuccess', 'Votre message a été envoyé avec succès');
            return $this->redirectToRoute('trick_details', ['id' => $id, 'slug' => $slug]);
        }

        return $this->renderForm('/frontoffice/detailsTrick.html.twig', [
            'trickDetails' => $trickDetails,
            'form' => $form
        ]);
    }

    /**
     * @param $id
     * @param $form
     * @param $message
     */
    private function messageTrick($id, $form, $message): void
    {
        $username = $this->getUser()->getUserIdentifier();
        $user = $this->userRepository->findOneBy(['username' => $username]);
        $trick = $this->trickRepository->findOneBy(['id' => $id]);
        $content = $form->get('content')->getData();
        $message->setUser($user);
        $message->setTrick($trick);
        $message->setContent($content);
        $em = $this->managerRegistry->getManager();
        $em->persist($message);
        $em->flush();
    }

    /**
     * @Route("/edit/{id}/{slug}", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_VISITOR")
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $id = $request->get('id');
        $slug = $request->get('slug');
        $editTrick = $this->trickRepository->getTrick($id);

        $trick = new Trick();
        $form = $this->createForm(EditTrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->update($id, $form, $slug);
        }
        return $this->renderForm('/frontoffice/editTrick.html.twig', [
            'editTrick' => $editTrick,
            'form' => $form
        ]);
    }

    /**
     * @param $id
     * @param $form
     * @param $slug
     */
    private function update($id, $form, $slug): void
    {
        $trick = $this->trickRepository->find($id);
        if (!$trick) {
            throw $this->createNotFoundException('Ce trick n\'existe pas');
        }
        $description = $form->get('description')->getData();
        $group_id = $form->get('group')->getData();
        $group = $this->groupRepository->findOneBy(['id' => $group_id]);
        $trick->setDescription($description);
        $trick->setGroup($group);
        $em = $this->managerRegistry->getManager();
        $em->flush();

        $this->addFlash('trickEditSuccess', 'Le trick a été modifié avec succès');
        $this->redirectToRoute('trick_edit', ['id' => $id, 'slug' => $slug]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_VISITOR")
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): redirectResponse
    {
        $trick_id = $request->get('id');
        $trickDelete = $this->trickRepository->find($trick_id);
        $em = $this->managerRegistry->getManager();
        $em->remove($trickDelete);
        $em->flush();
        $this->addFlash('successDeleteTrick', 'Le trick a été supprimé avec succès');
        return $this->redirectToRoute('app_homepage');
    }
}
