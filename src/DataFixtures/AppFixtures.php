<?php

namespace App\DataFixtures;

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
    }
}

