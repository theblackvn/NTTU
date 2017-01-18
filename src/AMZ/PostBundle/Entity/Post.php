<?php

namespace AMZ\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\PostBundle\Repository\PostRepository")
 * @ORM\Table(name="post", indexes={@ORM\Index(name="search_idx", columns={"slug"})})
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    const TYPE_PAGE = 'page';
    const TYPE_POST = 'post';
    const TYPE_STATIC_BLOCK = 'static_block';
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISH = 2;
    const STATUS_TRASH = 3;
    const ADMIN_NUMBER_ITEM_PER_PAGE = 10;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="posts_categories")
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="posts_tags")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="Gallery", mappedBy="post", fetch="EXTRA_LAZY")
     */
    private $gallery;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\Embedded(class="AMZ\BackendBundle\Entity\SEO", columnPrefix="seo_")
     */
    private $seo;

    /**
     * @ORM\Column(type="string", name="post_type", nullable=true, options={"comment": "post|page"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $template = 'standard';

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment":"draft|publish|trash"})
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFeatured = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $excerpt;

    /**
     * @ORM\Column(type="text", name="content", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $now = new \DateTime('now');
        $this->setUpdatedAt($now);
        $this->setCreatedAt($now);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $now = new \DateTime('now');
        $this->setUpdatedAt($now);
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     *
     * @return Post
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Post
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return Post
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     *
     * @return Post
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return boolean
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Post
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set seo
     *
     * @param \AMZ\BackendBundle\Entity\SEO $seo
     *
     * @return Post
     */
    public function setSeo(\AMZ\BackendBundle\Entity\SEO $seo)
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * Get seo
     *
     * @return \AMZ\BackendBundle\Entity\SEO
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \AMZ\PostBundle\Entity\Category $category
     *
     * @return Post
     */
    public function addCategory(\AMZ\PostBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AMZ\PostBundle\Entity\Category $category
     */
    public function removeCategory(\AMZ\PostBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add tag
     *
     * @param \AMZ\PostBundle\Entity\Tag $tag
     *
     * @return Post
     */
    public function addTag(\AMZ\PostBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AMZ\PostBundle\Entity\Tag $tag
     */
    public function removeTag(\AMZ\PostBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add gallery
     *
     * @param \AMZ\PostBundle\Entity\Gallery $gallery
     *
     * @return Post
     */
    public function addGallery(\AMZ\PostBundle\Entity\Gallery $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \AMZ\PostBundle\Entity\Gallery $gallery
     */
    public function removeGallery(\AMZ\PostBundle\Entity\Gallery $gallery)
    {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    public function getCategory()
    {
        $categories = $this->getCategories();
        if (empty($categories)) {
            return null;
        }
        return $categories[0];
    }
}
