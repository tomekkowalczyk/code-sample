<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\PostStatus;

/**
 * Class StatusFixtures.
 */
class PostStatusFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $postStatusesList = [
            'public' => 'Publiczny',
            'private' => 'Prywatny',
            'refuse_bin' => 'Kosz',
        ];

        foreach ($postStatusesList as $key => $name) {
            $postStatus = new PostStatus();
            $postStatus->setSlug($key);
            $postStatus->setName($name);

            $manager->persist($postStatus);
            $this->addReference('blog_status_'.$key, $postStatus);
        }

        $manager->flush();
    }
}
