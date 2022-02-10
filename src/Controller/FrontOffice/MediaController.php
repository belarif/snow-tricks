<?php

namespace App\Controller\FrontOffice;

use App\Repository\ImageRepository;
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

    public function __construct(
        EntityManagerInterface $em,
        ImageRepository        $imageRepository
    )
    {
        $this->em = $em;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @Route("/image/delete/{id}", name="image_delete")
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): redirectResponse
    {
        $image = $this->imageRepository->find($id);

        $this->em->remove($image);
        $this->em->flush();

        $this->addFlash('successDeleteImage', 'L\'image a été supprimé avec succès');
        return $this->redirectToRoute('trick_edit', ['id' => $image->getTrick()->getId(), 'slug' => $image->getTrick()->getSlug()]);
    }
}
