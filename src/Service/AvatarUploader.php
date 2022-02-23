<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class AvatarUploader {

    /**
     * @var string
     */
    private $avatarTargetDirectory;

    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(string $avatarTargetDirectory, SluggerInterface $slugger, Filesystem $filesystem) {
        $this->avatarTargetDirectory = $avatarTargetDirectory;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($this->avatarTargetDirectory, $fileName);

        return $fileName;
    }

    /**
     * @param String $avatar
     * @return void
     */
    public function removeImage(string $avatar): void {
        $this->filesystem->remove($this->avatarTargetDirectory . '/' . $avatar);
    }

}
