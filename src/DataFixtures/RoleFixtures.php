<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $roles = [['role' => 'visitor', 'roleName' => 'ROLE_VISITOR'], ['role' => 'admin', 'roleName' => 'ROLE_ADMIN']];
        foreach ($roles as $addRole) {
            $role = new Role();
            $role->setRole($addRole['role']);
            $role->setRoleName($addRole['roleName']);
            $manager->persist($role);
            $manager->flush();
        }
    }
}
