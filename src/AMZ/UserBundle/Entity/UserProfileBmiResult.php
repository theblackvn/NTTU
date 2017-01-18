<?php

namespace AMZ\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\UserBundle\Repository\UserProfileBmiResultRepository")
 * @ORM\Table(name="user_profile_bmi_result")
 * @ORM\HasLifecycleCallbacks
 */
class UserProfileBmiResult
{
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
     * @ORM\Column(type="smallint", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="UserProfile", inversedBy="profileBmiResults")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profile;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $weight;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $length;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $measureDate;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=true)
     */
    private $bmi;

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
     * Set category
     *
     * @param integer $category
     *
     * @return UserProfileBmiResult
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
     * Set subCategory
     *
     * @param integer $subCategory
     *
     * @return UserProfileBmiResult
     */
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get subCategory
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
     * @return UserProfileBmiResult
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
     * Set result
     *
     * @param string $result
     *
     * @return UserProfileBmiResult
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
     * Set resultType
     *
     * @param string $resultType
     *
     * @return UserProfileBmiResult
     */
    public function setResultType($resultType)
    {
        $this->resultType = $resultType;

        return $this;
    }

    /**
     * Get resultType
     *
     * @return string
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * Set resultValue
     *
     * @param string $resultValue
     *
     * @return UserProfileBmiResult
     */
    public function setResultValue($resultValue)
    {
        $this->resultValue = $resultValue;

        return $this;
    }

    /**
     * Get resultValue
     *
     * @return string
     */
    public function getResultValue()
    {
        return $this->resultValue;
    }

    /**
     * Set recommend
     *
     * @param string $recommend
     *
     * @return UserProfileBmiResult
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
     * Set advise
     *
     * @param string $advise
     *
     * @return UserProfileBmiResult
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
     * Set weight
     *
     * @param decimal $weight
     *
     * @return UserProfileBmiResult
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return decimal
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set length
     *
     * @param decimal $length
     *
     * @return UserProfileBmiResult
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return decimal
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set bmi
     *
     * @param decimal $bmi
     *
     * @return UserProfileBmiResult
     */
    public function setBmi($bmi)
    {
        $this->bmi = $bmi;

        return $this;
    }

    /**
     * Get bmi
     *
     * @return decimal
     */
    public function getBmi()
    {
        return $this->bmi;
    }

    /**
     * Set measureDate
     *
     * @param \DateTime $measureDate
     *
     * @return UserProfileBmiResult
     */
    public function setMeasureDate($measureDate)
    {
        $this->measureDate = $measureDate;

        return $this;
    }

    /**
     * Get measureDate
     *
     * @return \DateTime
     */
    public function getMeasureDate()
    {
        return $this->measureDate;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return UserProfileBmiResult
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
     * @return UserProfileBmiResult
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
     * Set profile
     *
     * @param \AMZ\UserBundle\Entity\UserProfile $profile
     *
     * @return UserProfileBmiResult
     */
    public function setProfile(\AMZ\UserBundle\Entity\UserProfile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AMZ\UserBundle\Entity\UserProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
