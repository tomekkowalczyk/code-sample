<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\PostStatus;

/**
 * Class PostType.
 */
class PostType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'post';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
                    'label' => 'Title',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '120',
                    ],
                ])
                ->add('slug', TextType::class, [
                    'label' => 'Slug',
                    'required' => false,
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '120',
                    ],
                ])
                ->add('category', EntityType::class, [
                    'label' => false,
                    'class' => Category::class,
                    'choice_value' => 'slug',
                ])
                ->add('status', EntityType::class, [
                    'label' => false,
                    'class' => PostStatus::class,
                    'choice_value' => 'slug',
                ])
                ->add('tags', EntityType::class, [
                    'label' => false,
                    'multiple' => true,
                    'class' => Tag::class,
                    'attr' => [
                        'multiple' => 'multiple',
                    ],
                    'choice_value' => 'slug',
                ])
                ->add('metaTitle', TextType::class, [
                    'label' => 'Meta title',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '60',
                    ],
                ])
                ->add('metaDescription', TextareaType::class, [
                    'label' => 'Meta description',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '160',
                        'rows' => 3,
                    ],
                ])
                ->add('content', TextareaType::class, [
                    'label' => 'Content',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '1000000',
                    ],
                ])
                ->add('introductionContent', TextareaType::class, [
                    'label' => 'Introduction content',
                    'attr' => [
                        'data-minlength' => '3',
                        'data-maxlength' => '500',
                        'rows' => 4,
                    ],
                ])
                ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => false,
                ])
                ->add('publishedDate', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label' => false,
                ])

               ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
