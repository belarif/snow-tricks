<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $tricks = [
            [
                'name' => 'mute',
                'group' => GroupFixtures::GROUP_TWO,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant'
            ],
            [
                'name' => 'stalefish',
                'group' => GroupFixtures::GROUP_TWO,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière'
            ],
            [
                'name' => 'Un rider regular',
                'group' => GroupFixtures::GROUP_ONE,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'Un rider regular aura son pied gauche devant'
            ],
            [
                'name' => 'Un 180',
                'group' => GroupFixtures::GROUP_THREE,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'un 180 désigne un demi-tour, soit 180 degrés d\'angle'
            ],
            [
                'name' => 'front flips',
                'group' => GroupFixtures::GROUP_FOUR,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'Un front flips est une rotation verticale en avant. 
                Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.
                Les flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), 
				mais de manière beaucoup plus rare, et se confondent souvent avec certaines 
				rotations horizontales désaxées'
            ],
            [
                'name' => 'nose slide',
                'group' => GroupFixtures::GROUP_SIX,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'Un slide consiste à glisser sur une barre de slide. 
                Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire,
                soit plus ou moins désaxé. Un nose slide, c\'est-à-dire l\'avant de la planche sur la barre'
            ],
            [
                'name' => 'truck driver',
                'group' => GroupFixtures::GROUP_TWO,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)'
            ],
            [
                'name' => 'nose grab',
                'group' => GroupFixtures::GROUP_TWO,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'saisie de la partie avant de la planche, avec la main avant'
            ],
            [
                'name' => 'un rideur goofy',
                'group' => GroupFixtures::GROUP_ONE,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'un rider goofy aura son pied droit devant'
            ],
            [
                'name' => 'un 720',
                'group' => GroupFixtures::GROUP_THREE,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'un 720, sept deux pour deux tours complets'
            ],
            [
                'name' => 'Back flips',
                'group' => GroupFixtures::GROUP_FOUR,
                'user' => UserFixtures::ADMIN_ONE,
                'description' => 'Les back flips, rotations en arrière. 
                Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation'
            ]
        ];

        foreach ($tricks as $trick) {
            $user = $this->getReference($trick['user']);
            $group = $this->getReference($trick['group']);

            $newTrick = new Trick();
            $newTrick->setName($trick['name']);
            $newTrick->setUser($user);
            $newTrick->setDescription($trick['description']);
            $newTrick->setGroup($group);
            $newTrick->setSlug(preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($trick['name']))));

            $manager->persist($newTrick);
            $manager->flush();
        }
    }

	public function getDependencies(): array
	{
		return [
			UserFixtures::class,
			GroupFixtures::class
		];
	}
}
