<?php

namespace AMZ\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AMZ\UserBundle\Repository\UserProfileRepository")
 * @ORM\Table(name="user_profiles")
 * @ORM\HasLifecycleCallbacks
 */
class UserProfile
{
    const ADMIN_NUMBER_ITEM_PER_PAGE = 20;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;
    const GROUP_1 = 1; // 0-5 age
    const GROUP_2 = 2; // 0-5 age
    const GROUP_3 = 3; // 0-5 age
    const GROUP_1_SLUG = '0-5-tuoi';
    const GROUP_2_SLUG = '5-19-tuoi';
    const GROUP_3_SLUG = 'tren-19-tuoi';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="UserProfileBmiResult", mappedBy="profile", fetch="EXTRA_LAZY")
     */
    private $profileBmiResults;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="profiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment": "male|female"})
     */
    private $gender;

    /**
     * @ORM\Column(type="smallint", nullable=true, options={"comment": "0-5 tuổi|5-19 tuổi|trên 19 tuổi"})
     */
    private $profileGroup;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
        $age = $this->getAge(date_format($this->getDateOfBirth(), 'm/d/Y'));
        if (5 > $age) {
            $this->setProfileGroup(self::GROUP_1);
        } elseif (5 <= $age && 19 >= $age) {
            $this->setProfileGroup(self::GROUP_2);
        } else {
            $this->setProfileGroup(self::GROUP_3);
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $now = new \DateTime('now');
        $this->setUpdatedAt($now);
        $age = $this->getAge(date_format($this->getDateOfBirth(), 'm/d/Y'));
        if (5 > $age) {
            $this->setProfileGroup(self::GROUP_1);
        } elseif (5 <= $age && 19 >= $age) {
            $this->setProfileGroup(self::GROUP_2);
        } else {
            $this->setProfileGroup(self::GROUP_3);
        }
    }

    public function getAge($birthDay)
    {
        //explode the date to get month, day and year
        $birthDay = explode("/", $birthDay);
        //get age from date or birthDay
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDay[0], $birthDay[1], $birthDay[2]))) > date("md")
            ? ((date("Y") - $birthDay[2]) - 1)
            : (date("Y") - $birthDay[2]));
        return $age;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return UserProfile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return UserProfile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return UserProfile
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return UserProfile
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
     * Set profileGroup
     *
     * @param integer $profileGroup
     *
     * @return UserProfile
     */
    public function setProfileGroup($profileGroup)
    {
        $this->profileGroup = $profileGroup;

        return $this;
    }

    /**
     * Get profileGroup
     *
     * @return integer
     */
    public function getProfileGroup()
    {
        return $this->profileGroup;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return UserProfile
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
     * @return UserProfile
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
     * @return UserProfile
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
     * @return UserProfile
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
     * Set user
     *
     * @param \AMZ\UserBundle\Entity\User $user
     *
     * @return UserProfile
     */
    public function setUser(\AMZ\UserBundle\Entity\User $user = null)
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
     * Constructor
     */
    public function __construct()
    {
        $this->profileBmiResults = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profileBmiResult
     *
     * @param \AMZ\UserBundle\Entity\UserProfileBmiResult $profileBmiResult
     *
     * @return UserProfile
     */
    public function addProfileBmiResult(\AMZ\UserBundle\Entity\UserProfileBmiResult $profileBmiResult)
    {
        $this->profileBmiResults[] = $profileBmiResult;

        return $this;
    }

    /**
     * Remove profileBmiResult
     *
     * @param \AMZ\UserBundle\Entity\UserProfileBmiResult $profileBmiResult
     */
    public function removeProfileBmiResult(\AMZ\UserBundle\Entity\UserProfileBmiResult $profileBmiResult)
    {
        $this->profileBmiResults->removeElement($profileBmiResult);
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
     */
    public $file;

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
        return $this;
    }
}
