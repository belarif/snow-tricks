<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class MediaUploader
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $targetDirectory, SluggerInterface $slugger, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $file->move($this->targetDirectory, $fileName);

        return $fileName;
    }

    /**
     * @param Image $image
     * @return void
     */
    public function removeImage(Image $image): void
    {
        $this->filesystem->remove($this->targetDirectory.'/'.$image->getSrc());
    }
}
