<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Role;
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
        /*** Creation of role ***/
        $role = new Role();
        $role->setRole('administrateur');
        $manager->persist($role);
        $manager->flush();
        /*** End creation of role ***/

        /*** Creation of user ***/
        $role_id = 1;
        $roleRepository = $manager->getRepository(Role::class);
        $role = $roleRepository->find($role_id);
        if ($role) {
            $user = new User();
            $user->setUsername('ocine');
            $user->setLastName('BELARIF');
            $user->setFirstName('Hocine');
            $user->setEmail('b.ocine@live.fr');
            $user->setPassword('admin1');
            $user->addRole($role);

            $manager->persist($user);
            $manager->flush();
        }
        /*** End creation of user ***/

        /*** Creation of group ***/
        $group = new Group();
        $group->setName('Les flips');
        $manager->persist($group);
        $manager->flush();
        /*** End creation of group ***/

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



