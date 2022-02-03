<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $tricks = [
            [
                'name' => 'mute',
                'group' => 2,
                'user' => 1,
                'description' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant'
            ],
            [
                'name' => 'stalefish',
                'group' => 2,
                'user' => 1,
                'description' => 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière'
            ],
            [
                'name' => 'Un rider regular',
                'group' => 1,
                'user' => 1,
                'description' => 'Un rider regular aura son pied gauche devant'
            ],
            [
                'name' => 'Un 180',
                'group' => 3,
                'user' => 1,
                'description' => 'un 180 désigne un demi-tour, soit 180 degrés d\'angle'
            ],
            [
                'name' => 'front flips',
                'group' => 4,
                'user' => 1,
                'description' => 'Un front flips est une rotation verticale en avant. 
                Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.
                Les flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), 
				mais de manière beaucoup plus rare, et se confondent souvent avec certaines 
				rotations horizontales désaxées'
            ],
            [
                'name' => 'nose slide',
                'group' => 6,
                'user' => 1,
                'description' => 'Un slide consiste à glisser sur une barre de slide. 
                Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire,
                soit plus ou moins désaxé. Un nose slide, c\'est-à-dire l\'avant de la planche sur la barre'
            ],
            [
                'name' => 'truck driver',
                'group' => 2,
                'user' => 1,
                'description' => 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)'
            ],
            [
                'name' => 'nose grab',
                'group' => 2,
                'user' => 1,
                'description' => 'saisie de la partie avant de la planche, avec la main avant'
            ],
            [
                'name' => 'un rideur goofy',
                'group' => 1,
                'user' => 1,
                'description' => 'un rider goofy aura son pied droit devant'
            ],
            [
                'name' => 'un 720',
                'group' => 3,
                'user' => 1,
                'description' => 'un 720, sept deux pour deux tours complets'
            ],
            [
                'name' => 'Back flips',
                'group' => 4,
                'user' => 1,
                'description' => 'Les back flips, rotations en arrière. 
                Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation'
            ]
        ];

        foreach ($tricks as $addTrick) {
            $userRepository = $manager->getRepository(User::class);
            $user = $userRepository->find($addTrick['user']);

            $groupRepository = $manager->getRepository(Group::class);
            $group = $groupRepository->find($addTrick['group']);

            $trick = new Trick();
            $trick->setName($addTrick['name']);
            $trick->setUser($user);
            $trick->setDescription($addTrick['description']);
            $trick->setGroup($group);
            $slug = preg_replace('/[^a-zA-Z0-9]+/i', '-', trim(strtolower($addTrick['name'])));
            $trick->setSlug($slug);
            $manager->persist($trick);
            $manager->flush();
        }
    }
}
