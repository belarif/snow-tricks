<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
	public const ROLE_ADMIN = 'ROLE_ADMIN';
	public const ROLE_VISITOR = 'ROLE_VISITOR';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $roles = [
			[
				'role' => 'visitor',
				'roleName' => self::ROLE_VISITOR
			],
	        [
				'role' => 'admin',
		        'roleName' => self::ROLE_ADMIN
	        ]
        ];

        foreach ($roles as $role) {
            $newRole = new Role();
            $newRole->setRole($role['role']);
            $newRole->setRoleName($role['roleName']);

            $manager->persist($newRole);
            $manager->flush();

			$this->addReference($role['roleName'], $newRole);
        }
    }
}
