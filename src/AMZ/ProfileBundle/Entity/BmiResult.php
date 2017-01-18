<?php

namespace AMZ\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\ProfileBundle\Repository\BmiResultRepository")
 * @ORM\Table(name="bmi_result")
 * @ORM\HasLifecycleCallbacks
 */
class BmiResult
{
    const ADMIN_NUMBER_ITEM_PER_PAGE = 10;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $subCategory;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $result;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $resultType;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $resultValue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $recommend;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $advise;

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
     * Constructor
     */
    public function __construct()
    {
        $this->classes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set Category
     *
     * @param string $category
     *
     * @return BmiResult
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Sub Category
     *
     * @param integer $subCategory
     *
     * @return BmiResult
     */
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get sub category
     *
     * @return integer
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }


    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return BmiResult
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return integer
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set Result
     *
     * @param string $result
     *
     * @return BmiResult
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set Result Type
     *
     * @param string $resultType
     *
     * @return BmiResult
     */
    public function setResultType($resultType)
    {
        $this->resultType = $resultType;
        return $this;
    }

    /**
     * Get result type
     *
     * @return string
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * Set Result Value
     *
     * @param string $resultValue
     *
     * @return BmiResult
     */
    public function setResultValue($resultValue)
    {
        $this->resultValue = $resultValue;
        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResultValue()
    {
        return $this->resultValue;
    }

    /**
     * Set Recommend
     *
     * @param string $recommend
     *
     * @return BmiResult
     */
    public function setRecommend($recommend)
    {
        $this->recommend = $recommend;
        return $this;
    }

    /**
     * Get recommend
     *
     * @return string
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * Set Advise
     *
     * @param string $advise
     *
     * @return BmiResult
     */
    public function setAdvise($advise)
    {
        $this->advise = $advise;
        return $this;
    }

    /**
     * Get advise
     *
     * @return string
     */
    public function getAdvise()
    {
        return $this->advise;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BmiResult
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
     * @return BmiResult
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
