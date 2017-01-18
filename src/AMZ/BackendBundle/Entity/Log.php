<?php

namespace AMZ\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\BackendBundle\Repository\LogRepository")
 * @ORM\Table(name="logs")
 * @ORM\HasLifecycleCallbacks
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="access_ip", type="string", length=15, nullable=true)
     */
    private $accessIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="access_time", type="datetime", nullable=false)
     */
    private $accessTime;

    /**
     * @var string
     *
     * @ORM\Column(name="event", type="string", length=200, nullable=true)
     */
    private $event;



    /**
     * @ORM\ManyToOne(targetEntity="AMZ\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=200, nullable=true)
     */
    private $entity;

    /**
     * @var integer
     *
     * @ORM\Column(name="entity_id", type="bigint", nullable=false)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=50, nullable=true)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="old_value", type="string", length=255, nullable=true)
     */
    private $oldValue;

    /**
     * @var string
     *
     * @ORM\Column(name="new_value", type="string", length=255, nullable=true)
     */
    private $newValue;

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
     * Get LogId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set accessIp
     *
     * @param string $accessIp
     * @return Log
     */
    public function setAccessIp($accessIp)
    {
        $this->accessIp = $accessIp;

        return $this;
    }

    /**
     * Get accessIp
     *
     * @return string
     */
    public function getAccessIp()
    {
        return $this->accessIp;
    }

    /**
     * Set accessTime
     *
     * @param \DateTime $accessTime
     * @return Log
     */
    public function setAccessTime($accessTime)
    {
        $this->accessTime = $accessTime;

        return $this;
    }

    /**
     * Get accessTime
     *
     * @return \DateTime
     */
    public function getAccessTime()
    {
        return $this->accessTime;
    }

    /**
     * Set event
     *
     * @param string $event
     * @return Log
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }



    /**
     * Set user
     *
     * @param \AMZ\UserBundle\Entity\User $user
     *
     * @return Log
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AMZ\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set entity
     *
     * @param string $entity
     * @return Log
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity id
     *
     * @param integer $entityId
     * @return Log
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entity id
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set field
     *
     * @param string $entity
     * @return Log
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set old value
     *
     * @param string $oldValue
     * @return Log
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    /**
     * Get old value
     *
     * @return string
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * Set new value
     *
     * @param string $newValue
     * @return Log
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
        return $this;
    }

    /**
     * Get old value
     *
     * @return string
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Log
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
     * @return Log
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
}
