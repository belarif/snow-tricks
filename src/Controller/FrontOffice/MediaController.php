<?php

namespace App\Controller\FrontOffice;

use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medias", name="media_")
 */
class MediaController extends AbstractController
{
    private $em;

    private $imageRepository;

    private $videoRepository;

    public function __construct(
        EntityManagerInterface $em,
        ImageRepository        $imageRepository,
        VideoRepository        $videoRepository
    )
    {
        $this->em = $em;
        $this->imageRepository = $imageRepository;
        $this->videoRepository = $videoRepository;
    }

    /**
     * @Route("/image/delete/{id}", name="image_delete")
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteImage(int $id): redirectResponse
    {
        $image = $this->imageRepository->find($id);

        $this->em->remove($image);
        $this->em->flush();

        $this->addFlash('successDeleteImage', 'L\'image a été supprimé avec succès');
        return $this->redirectToRoute('trick_edit', ['id' => $image->getTrick()->getId(), 'slug' => $image->getTrick()->getSlug()]);
    }

    /**
     * @Route("/video/delete/{id}", name="video_delete")
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteVideo(int $id): redirectResponse
    {
        $video = $this->videoRepository->find($id);

        $this->em->remove($video);
        $this->em->flush();

        $this->addFlash('successDeleteVideo', 'La vidéo a été supprimé avec succès');
        return $this->redirectToRoute('trick_edit', ['id' => $video->getTrick()->getId(), 'slug' => $video->getTrick()->getSlug()]);
    }

    /**
     * @Route("/image/edit/{id}, name="image_edit")
     */


}
