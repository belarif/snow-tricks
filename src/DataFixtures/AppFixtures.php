<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        /*** Creation of role ***/
        $role = new Role();
        $role->setRole('admin');
        $role->setRoleName('ROLE_ADMIN');

        $manager->persist($role);
        $manager->flush();
        /*** End creation of role ***/

        /** Creation of videos and images **/
        $trick_id = '1';
        $trickRepository = $manager->getRepository(Trick::class);
        $trick = $trickRepository->find($trick_id);

        $videos = ['https://www.youtube.com/embed/hih9jIzOoRg', 'https://www.youtube.com/embed/CA5bURVJ5zk',
            'https://www.youtube.com/embed/mfNA0UEJo1Y', 'https://www.youtube.com/embed/qsd8uaex-Is',
            'https://www.youtube.com/embed/V9xuy-rVj9w'];
        $images = ['mute-grab-1.png', 'mute-grab-2.png', 'mute-grab-3.png', 'mute-grab-4.png'];

        if ($trick) {
            foreach ($videos as $newVideo) {
                $video = new Video();
                $video->setSrc($newVideo);
                $video->setTrick($trick);
                $manager->persist($video);
                $manager->flush();
            }

            foreach ($images as $newImage) {
                $image = new Image();
                $image->setSrc($newImage);
                $image->setTrick($trick);
                $manager->persist($image);
                $manager->flush();
            }
        }
        /** End creation of videos and images **/
    }
}

