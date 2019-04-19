<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
/**
 * @ORM\Entity(repositoryClass="App\Repository\PostStatusRepository")
 * @ORM\Table(name="blog_status")
 */
class PostStatus extends AbstractTaxonomy
{
    /**
     * @ORM\OneToMany(
     *      targetEntity = "Post",
     *      mappedBy = "status"
     * )
     */
    protected $posts;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setStatus($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getStatus() === $this) {
                $post->setStatus(null);
            }
        }

        return $this;
    }
}
