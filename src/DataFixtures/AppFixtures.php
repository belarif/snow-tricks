<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\Type;
use App\Entity\User;
use App\Entity\Message;
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

        $type_id = 1;
        $typeRepository = $manager->getRepository(Type::class);
        $type = $typeRepository->find($type_id);

        if ($trick) {
            $media = new Media();
            $media->setSrc('mute-grab-3.jpg');
            $media->setTrick($trick);
            $media->setType($type);
            $manager->persist($media);
            $manager->flush($media);
        }
        /** End creation of media **/

        /** Creation of tricks **/
        $user_id = 1;
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->find($user_id);
        /*** create message ***/
        $user_id = 2;
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->find($user_id);

        $trick_id = 1;
        $trickRepository = $manager->getRepository(Trick::class);
        $trick = $trickRepository->find($trick_id);

        $message = new Message();
        $message->setTrick($trick);
        $message->setUser($user);
        $message->setContent('Ceci est le message test laisser par alex pour le trick mute de groupe grab');
        $manager->persist($message);
        $manager->flush();
        /*** End create message ***/
        $group_id = 4;
        $groupRepository = $manager->getRepository(Group::class);
        $group = $groupRepository->find($group_id);

        if ($user) {
            if ($group) {
                $trick = new Trick();
                $trick->setName('Back flips');
                $trick->setUser($user);
                $trick->setDescription('Les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.');
                $trick->setGroup($group);
                $slugName = preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower('Back flips')));
                $trick->setSlug($slugName);
                $manager->persist($trick);
                $manager->flush();
            }
        }
        /** End creation of tricks **/
    }
}

