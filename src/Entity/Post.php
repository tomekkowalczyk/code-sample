<?php

namespace App\Entity;

use App\Utils\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="blog_posts")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 *
 * @UniqueEntity(fields={"id"})
 */
class Post
{
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 120
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 120
     * )
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 60
     * )
     */
    private $metaTitle;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 160
     * )
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 100,
     *      max = 500
     * )
     */
    private $introductionContent;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 500000
     * )
     */
    private $content;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "Category",
     *      inversedBy = "posts"
     * )
     *
     * @ORM\JoinColumn(
     *      name = "category_id",
     *      referencedColumnName = "id",
     *      onDelete = "SET NULL"
     * )
     *
     * @Assert\NotBlank
     */
    private $category;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "PostStatus",
     *      inversedBy = "posts"
     * )
     *
     * @ORM\JoinColumn(
     *      name = "status_id",
     *      referencedColumnName = "id",
     *      onDelete = "SET NULL"
     * )
     *
     * @Assert\NotBlank
     */
    private $status;

    /**
     * @ORM\ManyToMany(
     *      targetEntity = "Tag",
     *      inversedBy = "posts"
     * )
     *
     * @ORM\JoinTable(
     *      name = "blog_tags"
     * )
     *
     * @Assert\Count(
     *      min=1,
     *      max=5
     * )
     */
    private $tags;

    /**
     * @ORM\ManyToOne(
     *      targetEntity = "App\Entity\User",
     *      inversedBy = "post"
     * )
     *
     * @ORM\JoinColumn(
     *      name = "author_id",
     *      referencedColumnName = "id"
     * )
     */
    private $author;

    /**
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $publishedDate = null;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(
     *     mapping="product_images",
     *     fileNameProperty="image"
     * )
     * @Assert\Image(
     *      minWidth = 500,
     *      minHeight = 300,
     *      maxWidth = 1920,
     *      maxHeight = 1080,
     *      maxSize = "1M"
     * )
     *
     * @var File
     */
    private $imageFile;

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updateDate = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publishedDate = new \DateTime();
        $this->createDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = Slugger::sluggify($slug);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setIntroductionContent(string $introductionContent): self
    {
        $this->introductionContent = $introductionContent;

        return $this;
    }

    public function getIntroductionContent(): ?string
    {
        return $this->introductionContent;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setPublishedDate(?\DateTimeInterface $publishedDate): self
    {
        $this->publishedDate = $publishedDate;

        return $this;
    }

    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->publishedDate;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setStatus(?PostStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?PostStatus
    {
        return $this->status;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }
}
