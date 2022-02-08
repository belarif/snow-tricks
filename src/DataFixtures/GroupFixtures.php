<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture
{
    public const GROUP_ONE = 'La manière de rideur';
    public const GROUP_TWO = 'Les grabs';
    public const GROUP_THREE = 'Les rotations';
    public const GROUP_FOUR = 'Les flips';
    public const GROUP_FIVE = 'Les rotations désaxées';
    public const GROUP_SIX = 'Les slides';
    public const GROUP_SEVEN = 'Les one foot tricks';
    public const GROUP_EIGHT = 'Old school';

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
            self::GROUP_SEVEN,
            self::GROUP_EIGHT,
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
