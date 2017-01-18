<?php

namespace AMZ\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\ProfileBundle\Repository\ProfileBmiResultRepository")
 * @ORM\Table(name="profile_bmi_result")
 * @ORM\HasLifecycleCallbacks
 */
class ProfileBmiResult
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SchoolClass", inversedBy="profileBmiResults")
     * @ORM\JoinColumn(name="school_class_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $schoolClass;

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
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="profileBmiResults")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $profile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dayWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $standardHeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $standardWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
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
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * Set recommend
     *
     * @param string $recommend
     *
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * Set profile
     *
     * @param \AMZ\ProfileBundle\Entity\Profile $profile
     *
     * @return ProfileBmiResult
     */
    public function setProfile(\AMZ\ProfileBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \AMZ\ProfileBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set bmi
     *
     * @param string $bmi
     *
     * @return ProfileBmiResult
     */
    public function setBmi($bmi)
    {
        $this->bmi = $bmi;

        return $this;
    }

    /**
     * Get bmi
     *
     * @return string
     */
    public function getBmi()
    {
        return $this->bmi;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ProfileBmiResult
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
     * @return ProfileBmiResult
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
     * Set dayWeight
     *
     * @param \DateTime $dayWeight
     *
     * @return ProfileBmiResult
     */
    public function setDayWeight($dayWeight)
    {
        $this->dayWeight = $dayWeight;

        return $this;
    }

    /**
     * Get dayWeight
     *
     * @return \DateTime
     */
    public function getDayWeight()
    {
        return $this->dayWeight;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return ProfileBmiResult
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return ProfileBmiResult
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set standard height
     *
     * @param string $height
     *
     * @return ProfileBmiResult
     */
    public function setStandardHeight($height)
    {
        $this->standardHeight = $height;

        return $this;
    }

    /**
     * Get standard height
     *
     * @return string
     */
    public function getStandardHeight()
    {
        return $this->standardHeight;
    }

    /**
     * Set standard weight
     *
     * @param string $weight
     *
     * @return ProfileBmiResult
     */
    public function setStandardWeight($weight)
    {
        $this->standardWeight = $weight;

        return $this;
    }

    /**
     * Get standard weight
     *
     * @return string
     */
    public function getStandardWeight()
    {
        return $this->standardWeight;
    }

    /**
     * Set schoolClass
     *
     * @param \AMZ\ProfileBundle\Entity\SchoolClass $schoolClass
     *
     * @return ProfileBmiResult
     */
    public function setSchoolClass(\AMZ\ProfileBundle\Entity\SchoolClass $schoolClass = null)
    {
        $this->schoolClass = $schoolClass;

        return $this;
    }

    /**
     * Get schoolClass
     *
     * @return \AMZ\ProfileBundle\Entity\SchoolClass
     */
    public function getSchoolClass()
    {
        return $this->schoolClass;
    }
}
