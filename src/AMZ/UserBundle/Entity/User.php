<?php

namespace AMZ\UserBundle\Entity;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("username")
 * @ORM\Entity(repositoryClass="AMZ\UserBundle\DependencyInjection\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    const ADMIN_NUMBER_ITEM_PER_PAGE = 20;
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_PRINCIPAL = 'ROLE_PRINCIPAL';
    const ROLE_CUSTOMER = 'ROLE_CUSTOMER';
    const ROLE_DOCTOR = 'ROLE_DOCTOR';

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array($this->role);
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="UserProfile", mappedBy="user", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"lastName" = "ASC"})
     */
    private $profiles;

    /**
     * @ORM\ManyToMany(targetEntity="AMZ\ProfileBundle\Entity\School", inversedBy="users")
     * @ORM\JoinTable(name="users_schools")
     */
    private $workSchools;

    /**
     * @ORM\ManyToOne(targetEntity="AMZ\ProfileBundle\Entity\School", inversedBy="employees")
     * @ORM\JoinColumn(name="work_for_school", referencedColumnName="id", onDelete="CASCADE")
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="AMZ\ProfileBundle\Entity\SchoolClass", inversedBy="teachers")
     * @ORM\JoinColumn(name="teacher_of_class", referencedColumnName="id", onDelete="CASCADE")
     */
    private $schoolClass;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $fullName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $job;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $workPlace;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked;

    /**
     * @ORM\Column(type="datetime", name="created_date")
     */
    protected $createdDate;

    /**
     * @ORM\Column(type="datetime", name="updated_date")
     */
    protected $updatedDate;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setDeleted(false);
        $this->setLocked(false);
        $now = new \DateTime('now');
        $this->setCreatedDate($now);
        $this->setUpdatedDate($now);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $now = new \DateTime('now');
        $this->setUpdatedDate($now);
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $salt = $this->generateSalt();
        $encoder = new MessageDigestPasswordEncoder();
        $password = $encoder->encodePassword($password, $salt);
        $this->salt = $salt;
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
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
     * @return User
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return User
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return User
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
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
     * Set phone
     *
     * @param string $phone
     *
     * @return User
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
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return User
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    public function generateSalt()
    {
        return md5(time());
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Constructor
     */
    public function __construct()
    {
        $this->profiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profile
     *
     * @param \AMZ\UserBundle\Entity\UserProfile $profile
     *
     * @return User
     */
    public function addProfile(\AMZ\UserBundle\Entity\UserProfile $profile)
    {
        $this->profiles[] = $profile;

        return $this;
    }

    /**
     * Remove profile
     *
     * @param \AMZ\UserBundle\Entity\UserProfile $profile
     */
    public function removeProfile(\AMZ\UserBundle\Entity\UserProfile $profile)
    {
        $this->profiles->removeElement($profile);
    }

    /**
     * Get profiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * Set school
     *
     * @param \AMZ\ProfileBundle\Entity\School $school
     *
     * @return User
     */
    public function setSchool(\AMZ\ProfileBundle\Entity\School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return \AMZ\ProfileBundle\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set schoolClass
     *
     * @param \AMZ\ProfileBundle\Entity\SchoolClass $schoolClass
     *
     * @return User
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

    /**
     * Add workSchool
     *
     * @param \AMZ\ProfileBundle\Entity\School $workSchool
     *
     * @return User
     */
    public function addWorkSchool(\AMZ\ProfileBundle\Entity\School $workSchool)
    {
        $this->workSchools[] = $workSchool;

        return $this;
    }

    /**
     * Remove workSchool
     *
     * @param \AMZ\ProfileBundle\Entity\School $workSchool
     */
    public function removeWorkSchool(\AMZ\ProfileBundle\Entity\School $workSchool)
    {
        $this->workSchools->removeElement($workSchool);
    }

    /**
     * Get workSchools
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkSchools()
    {
        return $this->workSchools;
    }

    /**
     * Set job
     *
     * @param string $job
     *
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set workPlace
     *
     * @param string $workPlace
     *
     * @return User
     */
    public function setWorkPlace($workPlace)
    {
        $this->workPlace = $workPlace;

        return $this;
    }

    /**
     * Get workPlace
     *
     * @return string
     */
    public function getWorkPlace()
    {
        return $this->workPlace;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return User
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
}
