<?php

namespace AMZ\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="AMZ\ProfileBundle\Repository\BmiForAgeRepository")
 * @ORM\Table(name="bmi_for_age")
 * @ORM\HasLifecycleCallbacks
 */
class BmiForAge
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
     * @ORM\Column(type="string", nullable=false)
     */
    private $yearMonth;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $month;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $gender;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $n3sd;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $n2sd;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $n1sd;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $median;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $p1sd;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $p2sd;

    /**
     * @ORM\Column(type="decimal", scale=1, nullable=false)
     */
    private $p3sd;


    /**
     * Set category
     *
     * @param smallint $category
     *
     * @return BmiForAge
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return smallint
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set yearMonth
     *
     * @param string $yearMonth
     *
     * @return BmiForAge
     */
    public function setYearMonth($yearMonth)
    {
        $this->yearMonth = $yearMonth;

        return $this;
    }

    /**
     * Get yearMonth
     *
     * @return string
     */
    public function getYearMonth()
    {
        return $this->yearMonth;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return BmiForAge
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     *
     * @return BmiForAge
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set n3sd
     *
     * @param string $n3sd
     *
     * @return BmiForAge
     */
    public function setN3sd($n3sd)
    {
        $this->n3sd = $n3sd;

        return $this;
    }

    /**
     * Get n3sd
     *
     * @return string
     */
    public function getN3sd()
    {
        return $this->n3sd;
    }

    /**
     * Set n2sd
     *
     * @param string $n2sd
     *
     * @return BmiForAge
     */
    public function setN2sd($n2sd)
    {
        $this->n2sd = $n2sd;

        return $this;
    }

    /**
     * Get n2sd
     *
     * @return string
     */
    public function getN2sd()
    {
        return $this->n2sd;
    }

    /**
     * Set n1sd
     *
     * @param string $n1sd
     *
     * @return BmiForAge
     */
    public function setN1sd($n1sd)
    {
        $this->n1sd = $n1sd;

        return $this;
    }

    /**
     * Get n1sd
     *
     * @return string
     */
    public function getN1sd()
    {
        return $this->n1sd;
    }

    /**
     * Set median
     *
     * @param string $median
     *
     * @return BmiForAge
     */
    public function setMedian($median)
    {
        $this->median = $median;

        return $this;
    }

    /**
     * Get median
     *
     * @return string
     */
    public function getMedian()
    {
        return $this->median;
    }

    /**
     * Set p1sd
     *
     * @param string $p1sd
     *
     * @return BmiForAge
     */
    public function setP1sd($p1sd)
    {
        $this->p1sd = $p1sd;

        return $this;
    }

    /**
     * Get p1sd
     *
     * @return string
     */
    public function getP1sd()
    {
        return $this->p1sd;
    }

    /**
     * Set 2sd
     *
     * @param string $p2sd
     *
     * @return BmiForAge
     */
    public function setP2sd($p2sd)
    {
        $this->p2sd = $p2sd;

        return $this;
    }

    /**
     * Get 2sd
     *
     * @return string
     */
    public function getP2sd()
    {
        return $this->p2sd;
    }

    /**
     * Set 3sd
     *
     * @param string $p3sd
     *
     * @return BmiForAge
     */
    public function setP3sd($p3sd)
    {
        $this->p3sd = $p3sd;

        return $this;
    }

    /**
     * Get 3sd
     *
     * @return string
     */
    public function getP3sd()
    {
        return $this->p3sd;
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
}
