<?php

namespace AMZ\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\PostBundle\Repository\GalleryRepository")
 * @ORM\Table(name="post_gallery")
 * @ORM\HasLifecycleCallbacks
 */
class Gallery
{
    const ADMIN_NUMBER_ITEM_PER_PAGE = 20;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="gallery")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $smallSizeThumbnail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullSizeThumbnail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

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
     * @return Gallery
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
     * Set smallSizeThumbnail
     *
     * @param string $smallSizeThumbnail
     *
     * @return Gallery
     */
    public function setSmallSizeThumbnail($smallSizeThumbnail)
    {
        $this->smallSizeThumbnail = $smallSizeThumbnail;

        return $this;
    }

    /**
     * Get smallSizeThumbnail
     *
     * @return string
     */
    public function getSmallSizeThumbnail()
    {
        return $this->smallSizeThumbnail;
    }

    /**
     * Set fullSizeThumbnail
     *
     * @param string $fullSizeThumbnail
     *
     * @return Gallery
     */
    public function setFullSizeThumbnail($fullSizeThumbnail)
    {
        $this->fullSizeThumbnail = $fullSizeThumbnail;

        return $this;
    }

    /**
     * Get fullSizeThumbnail
     *
     * @return string
     */
    public function getFullSizeThumbnail()
    {
        return $this->fullSizeThumbnail;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Gallery
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Gallery
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
     * @return Gallery
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
     * Set post
     *
     * @param \AMZ\PostBundle\Entity\Post $post
     *
     * @return Gallery
     */
    public function setPost(\AMZ\PostBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \AMZ\PostBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
