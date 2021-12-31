<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
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
    }
}
