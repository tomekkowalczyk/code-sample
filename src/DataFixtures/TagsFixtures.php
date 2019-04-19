<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Tag;

/**
 * Class TagsFixtures.
 */
class TagsFixtures extends Fixture
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tagsList = [
            'dolor',
            'ullamcorper',
            'suspendisse',
            'pellentesque',
            'maecenas',
            'malesuada',
            'ultricies',
            'etiam',
            'quisque',
            'fringilla',
            'eleifend',
            'bibendum',
            'faucibus',
            'luctus',
            'vestibulum',
            'testing-tag',
        ];

        foreach ($tagsList as $key => $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tag->setSlug($name);

            $manager->persist($tag);
            $this->addReference('tag_'.$name, $tag);
        }

        $manager->flush();
    }
}
