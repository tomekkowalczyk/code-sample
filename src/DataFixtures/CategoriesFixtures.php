<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

/**
 * Class CategoriesFixtures.
 */
class CategoriesFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $categoriesList = [
            'pellentesque' => 'Pellentesque',
            'maecenas' => 'Maecenas',
            'lobortis' => 'Lobortis',
            'varius' => 'Varius',
            'testing-category' => 'Testing do not remove',
        ];

        foreach ($categoriesList as $key => $name) {
            $category = new Category();
            $category->setName($name);
            $category->setSlug($key);

            $manager->persist($category);
            $this->addReference('category_'.$key, $category);
        }

        $manager->flush();
    }
}
