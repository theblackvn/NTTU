<?php

namespace AMZ\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\SliderBundle\Repository\ItemRepository")
 * @ORM\Table(name="slider_items")
 * @ORM\HasLifecycleCallbacks
 */
class Item
{
    const ADMIN_NUMBER_ITEM_PER_PAGE = 20;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Position", inversedBy="items")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $position;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $smallSizeThumbnail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullSizeThumbnail;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $sortOrder = 1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="text", name="content", nullable=true)
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
     * Set smallSizeThumbnail
     *
     * @param string $smallSizeThumbnail
     *
     * @return Item
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
     * @return Item
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
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return Item
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Item
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
     * Set content
     *
     * @param string $content
     *
     * @return Item
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
     * @return Item
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
     * @return Item
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
     * Set position
     *
     * @param \AMZ\SliderBundle\Entity\Position $position
     *
     * @return Item
     */
    public function setPosition(\AMZ\SliderBundle\Entity\Position $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return \AMZ\SliderBundle\Entity\Position
     */
    public function getPosition()
    {
        return $this->position;
    }
}
