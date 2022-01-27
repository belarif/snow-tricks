<?php

namespace App\Controller\FrontOffice;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\CreateTrickType;
use App\Form\EditTrickType;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/create", name="create")
     * @IsGranted("ROLE_VISITOR")
     */
    public function new(Request $request, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {
        $trick = new Trick();
        $form = $this->createForm(CreateTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $img) {
                $file = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $img->guessExtension();
                $img->move(
                    $this->getParameter('app.images_directory'),
                    $file
                );
                $image = new Image();
                $image->setSrc($file);
                $trick->addImage($image);
            }

            $username = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(array('username' => $username));
            $name = $form->get('name')->getData();
            $slug = preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($name)));
            $trick->setSlug($slug);
            $trick->setUser($user);
            $em = $doctrine->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash('successCreateTrick', 'Le trick a été créé avec succès');
            return $this->redirectToRoute('app_homepage');
        }
        return $this->renderForm('/frontoffice/createTrick.html.twig', array('form' => $form, 'trick' => $trick));
    }

    /**
     * @Route("/details/{id}/{slug}", name="details")
     */
    public function show(
        TrickRepository $trickRepository,
        Request         $request,
        ImageRepository $imageRepository,
        VideoRepository $videoRepository
    ): Response
    {
        $id = $request->get('id');
        $trickDetails = $trickRepository->getTrick($id);
        $imagesTrick = $imageRepository->getImagesTrick($id);
        $videosTrick = $videoRepository->getVideosTrick($id);

        return $this->render('/frontoffice/detailsTrick.html.twig', [
            'trickDetails' => $trickDetails,
            'videosTrick' => $videosTrick,
            'imagesTrick' => $imagesTrick
        ]);
    }

    /**
     * @Route("/edit/{id}/{slug}", name="edit")
     * @IsGranted("ROLE_VISITOR")
     */
    public function edit(Request $request, TrickRepository $trickRepository): Response
    {
        $id = $request->get('id');
        $editTrick = $trickRepository->getTrick($id);
        $trick = new Trick();
        $form = $this->createForm(EditTrickType::class, $trick);

        return $this->renderForm('/frontoffice/editTrick.html.twig', array('editTrick' => $editTrick, 'form' => $form));
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_VISITOR")
     */
    public function delete(TrickRepository $trickRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $trick_id = $request->get('id');
        $trickDelete = $trickRepository->find($trick_id);
        $em = $doctrine->getManager();
        $em->remove($trickDelete);
        $em->flush();
        $this->addFlash('successDeleteTrick', 'Le trick a été supprimé avec succès');
        return $this->redirectToRoute('app_homepage');
    }
}