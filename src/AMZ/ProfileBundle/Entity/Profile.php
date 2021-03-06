<?php

namespace AMZ\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AMZ\ProfileBundle\Repository\ProfileRepository")
 * @ORM\Table(name="profile")
 * @ORM\HasLifecycleCallbacks
 */
class Profile
{
    const ADMIN_NUMBER_ITEM_PER_PAGE = 20;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="SchoolClass", mappedBy="profiles")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="ProfileBmiResult", mappedBy="profile", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $profileBmiResults;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $district;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment": "male|female"})
     */
    private $gender;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastHeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastBMI;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastResult;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastDayWeight;

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
     * Set name
     *
     * @param string $name
     *
     * @return Profile
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Profile
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Profile
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return Profile
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Profile
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Profile
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return Profile
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
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Profile
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Profile
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Profile
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
     * @return Profile
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
     * Add class
     *
     * @param \AMZ\ProfileBundle\Entity\SchoolClass $class
     *
     * @return Profile
     */
    public function addClass(\AMZ\ProfileBundle\Entity\SchoolClass $class)
    {
        $this->classes[] = $class;

        return $this;
    }

    /**
     * Remove class
     *
     * @param \AMZ\ProfileBundle\Entity\SchoolClass $class
     */
    public function removeClass(\AMZ\ProfileBundle\Entity\SchoolClass $class)
    {
        $this->classes->removeElement($class);
    }

    /**
     * Get classes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Add profile bmi result
     *
     * @param \AMZ\ProfileBundle\Entity\ProfileBmiResult $profileBmiResult
     *
     * @return Profile
     */
    public function addProfileBmiResult(\AMZ\ProfileBundle\Entity\ProfileBmiResult $profileBmiResult)
    {
        $this->profileBmiResults[] = $profileBmiResult;

        return $this;
    }

    /**
     * Remove profile bmi result
     *
     * @param \AMZ\ProfileBundle\Entity\ProfileBmiResult $profileBmiResult
     */
    public function removeProfileBmiResult(\AMZ\ProfileBundle\Entity\ProfileBmiResult $profileBmiResult)
    {
        $this->profileBmiResults->removeElement($profileBmiResult);
    }

    /**
     * Get profile bmi result
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfileBmiResult()
    {
        return $this->profileBmiResults;
    }

    public function getProfileId()
    {
        $id = $this->getId();
        $profileId = 'HS';
        for ($i = 0; $i <= (6 - strlen($id)); $i++) {
            $profileId .= '0';
        }
        return $profileId . $id;
    }

    /**
     * Get profileBmiResults
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfileBmiResults()
    {
        return $this->profileBmiResults;
    }

    /**
     * Set lastHeight
     *
     * @param string $lastHeight
     *
     * @return Profile
     */
    public function setLastHeight($lastHeight)
    {
        $this->lastHeight = $lastHeight;

        return $this;
    }

    /**
     * Get lastHeight
     *
     * @return string
     */
    public function getLastHeight()
    {
        return $this->lastHeight;
    }

    /**
     * Set lastWeight
     *
     * @param string $lastWeight
     *
     * @return Profile
     */
    public function setLastWeight($lastWeight)
    {
        $this->lastWeight = $lastWeight;

        return $this;
    }

    /**
     * Get lastWeight
     *
     * @return string
     */
    public function getLastWeight()
    {
        return $this->lastWeight;
    }

    /**
     * Set lastBMI
     *
     * @param string $lastBMI
     *
     * @return Profile
     */
    public function setLastBMI($lastBMI)
    {
        $this->lastBMI = $lastBMI;

        return $this;
    }

    /**
     * Get lastBMI
     *
     * @return string
     */
    public function getLastBMI()
    {
        return $this->lastBMI;
    }

    /**
     * Set lastResult
     *
     * @param string $lastResult
     *
     * @return Profile
     */
    public function setLastResult($lastResult)
    {
        $this->lastResult = $lastResult;

        return $this;
    }

    /**
     * Get lastResult
     *
     * @return string
     */
    public function getLastResult()
    {
        return $this->lastResult;
    }

    /**
     * Set lastDayWeight
     *
     * @param \DateTime $lastDayWeight
     *
     * @return Profile
     */
    public function setLastDayWeight($lastDayWeight)
    {
        $this->lastDayWeight = $lastDayWeight;

        return $this;
    }

    /**
     * Get lastDayWeight
     *
     * @return \DateTime
     */
    public function getLastDayWeight()
    {
        return $this->lastDayWeight;
    }
}
