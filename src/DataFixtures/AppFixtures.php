<?php

namespace App\DataFixtures;

use App\Entity\Group;
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
        /** Creation of tricks **/
        $user_id = 1;
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->find($user_id);

        $group_id = 4;
        $groupRepository = $manager->getRepository(Group::class);
        $group = $groupRepository->find($group_id);

        if ($user) {
            if ($group) {
                $trick = new Trick();
                $trick->setName('Back flips');
                $trick->setUser($user);
                $trick->setDescription('Les back flips, rotations en arrière.
                Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.');
                $trick->setGroup($group);
                $manager->persist($trick);
                $manager->flush();
            }
        }
        /** End creation of tricks **/
    }
}



