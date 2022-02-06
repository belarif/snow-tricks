<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
	public const ADMIN_ONE = 'ADMIN_ONE';
	public const ADMIN_TWO = 'ADMIN_TWO';

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
		$users = [
			[
				'username' =>  'belarif',
				'email' => 'hocine.belarif1@gmail.com',
				'password' => 'admin',
				'level' => self::ADMIN_ONE
			],
			[
				'username' =>  'bobo',
				'email' => 'bobo@gmail.com',
				'password' => 'bobo',
				'level' => self::ADMIN_TWO
			],
		];

	    foreach ($users as $user) {
		    $newUser = new User();
		    $newUser->setUsername($user['username']);
		    $newUser->setEmail($user['email']);
		    $newUser->setPassword($this->passwordHasher->hashPassword($newUser, $user['password']));
		    $newUser->addRole($this->getReference(RoleFixtures::ROLE_ADMIN));
		    $newUser->setEnabled(true);
		    $newUser->setProfileStatus(false);

		    $manager->persist($newUser);
		    $manager->flush();

			$this->setReference($user['level'], $newUser);
		}
    }

	public function getDependencies(): array
	{
		return [
			RoleFixtures::class
		];
	}
}

