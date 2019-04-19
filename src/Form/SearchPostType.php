<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class SearchPostType.
 */
class SearchPostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, [
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => 'App\Entity\Category',
            ])
            ->add('status', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => 'App\Entity\PostStatus',
            ])
            ->add('tags', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => 'App\Entity\Tag',
            ])
            ;
    }
}
