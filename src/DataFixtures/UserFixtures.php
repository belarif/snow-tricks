<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $role_id = 1;
        $roleRepository = $manager->getRepository(Role::class);
        $role = $roleRepository->find($role_id);
        if ($role) {
            $user = new User();
            $user->setUsername('belarif');
            $user->setEmail('hocine.belarif1@gmail.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
            $user->addRole($role);
            $user->setEnabled(true);
            $user->setProfileStatus(false);
            $manager->persist($user);
            $manager->flush();
        }
    }
}

