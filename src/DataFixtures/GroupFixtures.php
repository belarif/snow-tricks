<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture
{
	public const GROUP_ONE = 'GROUP_ONE';
	public const GROUP_TWO = 'GROUP_TWO';
	public const GROUP_THREE = 'GROUP_THREE';
	public const GROUP_FOUR = 'GROUP_FOUR';
	public const GROUP_FIVE = 'GROUP_FIVE';
	public const GROUP_SIX = 'GROUP_SIX';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $groups = [
			self::GROUP_ONE,
	        self::GROUP_TWO,
			self::GROUP_THREE,
			self::GROUP_FOUR,
			self::GROUP_FIVE,
			self::GROUP_SIX,
        ];

        foreach ($groups as $name) {
            $group = new Group();
            $group->setName($name);

            $manager->persist($group);
            $manager->flush();

			$this->addReference($name, $group);
        }
    }
}
