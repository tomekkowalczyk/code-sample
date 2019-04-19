<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Post;

/**
 * Class PostsFixtures.
 */
class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $postsList = [
            [
                'title' => 'Testing post don\'t remove please',
                'metaTitle' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'slug' => 'testing-post',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam interdum lectus sed dolor mollis, et iaculis ipsum tristique. Sed varius tristique justo, ornare molestie leo. Donec libero tellus, efficitur at nunc a, tristique ultrices velit. Proin ac odio interdum, gravida urna sit amet, dapibus velit. Donec pretium at lorem non scelerisque. Vestibulum posuere tempor purus at scelerisque. Quisque egestas consequat orci, eget accumsan mauris ultrices id. Integer a faucibus sem. In non mi eget velit placerat elementum id condimentum diam. Duis vulputate metus at sollicitudin rutrum. Sed mi dui, luctus placerat urna eget, posuere euismod felis. Morbi quis dui vitae est laoreet facilisis. In massa justo, faucibus eget venenatis et, luctus eget risus. In gravida, velit in mattis imperdiet, mauris velit finibus dui, ut ultrices diam nisi eget lectus. Maecenas mattis nulla magna, ut cursus neque fringilla ut. Proin auctor pharetra diam sollicitudin porta. Pellentesque finibus massa id orci dictum condimentum vitae nec nibh. Vestibulum interdum tortor non sapien pulvinar dignissim. Cras in nibh nec est suscipit placerat. Vivamus gravida vitae odio sit amet auctor. Donec ac velit non leo ornare scelerisque. Sed aliquam odio ac vulputate congue. Proin viverra nisi tellus, et suscipit ipsum ornare ac. Quisque at nunc quam.',
                'introductionContent' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam interdum lectus sed dolor mollislestie leo.',
                'metaDescription' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam interdum lectus sed dolor mollislestie leo.',
                'category' => 'pellentesque',
                'status' => 'public',
                'tags' => ['dolor', 'suspendisse', 'quisque', 'luctus'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-05-01 21:18:12',
                'image' => 'zajęcia indywidualne dzieci.jpg',
            ],
            [
                'title' => 'Dolor sit amet, consectetur adipiscing elit',
                'metaTitle' => 'Dolor sit amet, consectetur adipiscing elit',
                'slug' => 'dolor sit amet, consectetur adipiscing elit',
                'content' => 'Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi at tincidunt risus. Fusce maximus nisl lacus, id accumsan lacus iaculis at. Duis semper tristique ligula id malesuada. Sed cursus sem quis mi vulputate, id condimentum nisi ullamcorper. Nulla in diam gravida sapien suscipit egestas. Morbi ut lacinia urna. Morbi iaculis magna sit amet tellus vulputate tincidunt. Mauris a quam ligula.',
                'introductionContent' => 'Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi at tincidunt risus. Fusce maximus nisl lacus.',
                'metaDescription' => 'Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi at tincidunt risus. Fusce maximus nisl lacus.',
                'category' => 'varius',
                'status' => 'public',
                'tags' => ['dolor', 'luctus'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-01-01 15:10:13',
                'image' => null,
            ],
            [
                'title' => 'Consectetur adipiscing elit',
                'metaTitle' => 'Consectetur adipiscing elit',
                'slug' => 'onsectetur adipiscing elit',
                'content' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis. Vivamus fermentum ex vel lobortis venenatis. Aenean elementum pretium iaculis. Sed accumsan urna est, id volutpat lacus blandit sit amet. Aenean eleifend purus eget leo elementum faucibus. Duis eu viverra turpis, nec tincidunt justo. Nullam tempus at libero eu viverra. Nulla gravida nibh in sapien efficitur semper. Vestibulum vehicula leo felis, vel ornare mauris convallis non. Mauris suscipit eu augue vel pharetra. Ut congue vitae arcu ac placerat. Vivamus porta elit sed orci pretium, mollis auctor est rutrum. Morbi pulvinar imperdiet tellus non volutpat.',
                'introductionContent' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'metaDescription' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'category' => 'pellentesque',
                'status' => 'private',
                'tags' => ['suspendisse'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-02-01 19:19:12',
                'image' => 'zajęcia indywidualne dzieci.jpg',
            ],
            [
                'title' => 'Adipiscing elit',
                'metaTitle' => 'Consectetur adipiscing elit',
                'slug' => 'nsectetur adipiscing elit',
                'content' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis. Vivamus fermentum ex vel lobortis venenatis. Aenean elementum pretium iaculis. Sed accumsan urna est, id volutpat lacus blandit sit amet. Aenean eleifend purus eget leo elementum faucibus. Duis eu viverra turpis, nec tincidunt justo. Nullam tempus at libero eu viverra. Nulla gravida nibh in sapien efficitur semper. Vestibulum vehicula leo felis, vel ornare mauris convallis non. Mauris suscipit eu augue vel pharetra. Ut congue vitae arcu ac placerat. Vivamus porta elit sed orci pretium, mollis auctor est rutrum. Morbi pulvinar imperdiet tellus non volutpat.',
                'introductionContent' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'metaDescription' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'category' => 'pellentesque',
                'status' => 'private',
                'tags' => ['dolor', 'luctus'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-09-23 18:12:12',
                'image' => 'strona główna slider2.jpg',
            ],
            [
                'title' => 'Consectetur adipiscing elit Consectetur adipiscing elit',
                'metaTitle' => 'Consectetur adipiscing elit',
                'slug' => 'sectetur adipiscing elit',
                'content' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis. Vivamus fermentum ex vel lobortis venenatis. Aenean elementum pretium iaculis. Sed accumsan urna est, id volutpat lacus blandit sit amet. Aenean eleifend purus eget leo elementum faucibus. Duis eu viverra turpis, nec tincidunt justo. Nullam tempus at libero eu viverra. Nulla gravida nibh in sapien efficitur semper. Vestibulum vehicula leo felis, vel ornare mauris convallis non. Mauris suscipit eu augue vel pharetra. Ut congue vitae arcu ac placerat. Vivamus porta elit sed orci pretium, mollis auctor est rutrum. Morbi pulvinar imperdiet tellus non volutpat.',
                'introductionContent' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'metaDescription' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'category' => 'pellentesque',
                'status' => 'private',
                'tags' => ['suspendisse'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-02-22 19:10:10',
                'image' => 'strona główna slider2.jpg',
            ],
            [
                'title' => 'Cons ecte tur adipiscing elit Consectetur adipiscing elit',
                'metaTitle' => 'Consectetur adipiscing elit',
                'slug' => 'ectetur adipiscing elit',
                'content' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis. Vivamus fermentum ex vel lobortis venenatis. Aenean elementum pretium iaculis. Sed accumsan urna est, id volutpat lacus blandit sit amet. Aenean eleifend purus eget leo elementum faucibus. Duis eu viverra turpis, nec tincidunt justo. Nullam tempus at libero eu viverra. Nulla gravida nibh in sapien efficitur semper. Vestibulum vehicula leo felis, vel ornare mauris convallis non. Mauris suscipit eu augue vel pharetra. Ut congue vitae arcu ac placerat. Vivamus porta elit sed orci pretium, mollis auctor est rutrum. Morbi pulvinar imperdiet tellus non volutpat.',
                'introductionContent' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'metaDescription' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'category' => 'pellentesque',
                'status' => 'public',
                'tags' => ['suspendisse'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-12-12 19:10:10',
                'image' => null,
            ],
            [
                'title' => 'Adipiscing elit Consectetur adipiscing elit',
                'metaTitle' => 'Consectetur adipiscing elit',
                'slug' => 'ctetur adipiscing elit',
                'content' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis. Vivamus fermentum ex vel lobortis venenatis. Aenean elementum pretium iaculis. Sed accumsan urna est, id volutpat lacus blandit sit amet. Aenean eleifend purus eget leo elementum faucibus. Duis eu viverra turpis, nec tincidunt justo. Nullam tempus at libero eu viverra. Nulla gravida nibh in sapien efficitur semper. Vestibulum vehicula leo felis, vel ornare mauris convallis non. Mauris suscipit eu augue vel pharetra. Ut congue vitae arcu ac placerat. Vivamus porta elit sed orci pretium, mollis auctor est rutrum. Morbi pulvinar imperdiet tellus non volutpat.',
                'introductionContent' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'metaDescription' => 'Maecenas vel enim ut sem tincidunt ornare. Vivamus volutpat sodales massa, nec tempor eros iaculis quis.',
                'category' => 'pellentesque',
                'status' => 'public',
                'tags' => ['suspendisse'],
                'author' => 513541,
                'createDate' => null,
                'publishedDate' => '2019-01-30 21:11:10',
                'image' => null,
            ],
        ];

        foreach ($postsList as $idx => $details) {
            $post = new Post();

            $post->setTitle($details['title'])
                    ->setContent($details['content'])
                    ->setMetaTitle($details['metaTitle'])
                    ->setMetaDescription($details['metaDescription'])
                    ->setSlug($details['slug'])
                    ->setIntroductionContent($details['introductionContent'])
                    ->setCreateDate(new \DateTime($details['createDate']));

            if (null !== $details['publishedDate']) {
                $post->setPublishedDate(new \DateTime($details['publishedDate']));
            }
            if (null !== $details['author']) {
                $post->setAuthor($this->getReference('user_'.$details['author']));
            }
            if (null !== $details['image']) {
                $post->setImage($details['image']);
            }

            $post->setCategory($this->getReference('category_'.$details['category']));
            $post->setStatus($this->getReference('blog_status_'.$details['status']));

            foreach ($details['tags'] as $tagName) {
                $post->addTag($this->getReference('tag_'.$tagName));
            }

            $this->addReference('post_'.$idx, $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PostStatusFixtures::class,
            CategoriesFixtures::class,
            TagsFixtures::class,
        ];
    }
}
