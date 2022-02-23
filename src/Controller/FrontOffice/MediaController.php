<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace App\Controller\FrontOffice;

use App\Entity\Image;
use App\Entity\Video;
use App\Form\ImageType;
use App\Form\VideoType;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/image/delete/{id}", name="image_delete", requirements={"id"="\d+"})
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
        return $this->redirectToRoute('trick_edit', [
            'id' => $image->getTrick()->getId(),
            'slug' => $image->getTrick()->getSlug()
        ]);
    }

    /**
     * @Route("/video/delete/{id}", name="video_delete",
     *     requirements={"id"="\d+"})
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
        return $this->redirectToRoute('trick_edit', [
            'id' => $video->getTrick()->getId(),
            'slug' => $video->getTrick()->getSlug()
        ]);
    }

    /**
     * @Route("/image/edit/{id}", name="image_edit", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function editImage(Request $request, int $id): RedirectResponse
    {
        $image = new Image();
        $formImage = $this->createForm(ImageType::class, $image);
        $formImage->handleRequest($request);

        if ($formImage->isSubmitted() && $formImage->isValid()) {

            $image = $this->imageRepository->findOneBy(['id' => $id]);
            $slug = $image->getTrick()->getSlug();
            $trick_id = $image->getTrick()->getId();

            $src = $formImage->get('src')->getData();
            if ($src === null) {
                return $this->redirectToRoute('trick_edit', [
                    'id' => $trick_id, 'slug' => $slug
                ]);
            }

            $file = pathinfo($src->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $src->guessExtension();
            $src->move($this->getParameter('app.images_directory'), $file);

            $image->setSrc($file);
            $this->em->flush();

            return $this->redirectToRoute('trick_edit', [
                'id' => $trick_id,
                'slug' => $slug
            ]);
        }
    }

    /**
     * @Route("/video/edit/{id}", name="video_edit", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function editVideo(Request $request, int $id): RedirectResponse
    {
        $video = new Video();
        $formVideo = $this->createForm(VideoType::class, $video);
        $formVideo->handleRequest($request);

        if ($formVideo->isSubmitted() && $formVideo->isValid()) {

            $video = $this->videoRepository->findOneBy(['id' => $id]);
            $slug = $video->getTrick()->getSlug();
            $trick_id = $video->getTrick()->getId();

            $src = $formVideo->get('src')->getData();
            if ($src === null) {
                return $this->redirectToRoute('trick_edit', [
                    'id' => $trick_id,
                    'slug' => $slug
                ]);
            }

            $video->setSrc($src);
            $this->em->flush();

            return $this->redirectToRoute('trick_edit', [
                'id' => $trick_id,
                'slug' => $slug
            ]);
        }
    }
}
