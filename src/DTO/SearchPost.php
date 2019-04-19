<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SearchPost
{
    /**
     * @Assert\Length(
     *      max = 120
     * )
     */
    public $search;

    public $category;

    public $status;

    public $tags;

    /**
     * Get search.
     *
     * @return string search
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * Get category.
     *
     * @return App\Entity\Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }
}
