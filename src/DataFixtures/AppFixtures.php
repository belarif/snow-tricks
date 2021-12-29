<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        /*** Creation of types ***/
        $types = ['image', 'video'];
        foreach ($types as $newType) {
            $type = new Type();
            $type->setName($newType);
            $manager->persist($type);
            $manager->flush();
        }
        /*** End creation of types ***/

        /** Creation of media **/
        $trick_id = '1';
        $trickRepository = $manager->getRepository(Trick::class);
        $trick = $trickRepository->find($trick_id);

        $type_id = 2;
        $typeRepository = $manager->getRepository(Type::class);
        $type = $typeRepository->find($type_id);

        if ($trick) {
            $media = new Media();
            $media->setSrc('https://www.youtube.com/embed/hih9jIzOoRg');
            $media->setTrick($trick);
            $media->setType($type);
            $manager->persist($media);
            $manager->flush($media);
        }
        /** End creation of media **/
    }
}


