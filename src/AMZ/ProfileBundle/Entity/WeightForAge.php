<?php

namespace AMZ\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="AMZ\ProfileBundle\Repository\WeightForAgeRepository")
 * @ORM\Table(name="weight_for_age")
 * @ORM\HasLifecycleCallbacks
 */
class WeightForAge
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
     * @ORM\Column(type="integer", scale=1, nullable=false)
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
     * @param boolean $category
     *
     * @return WeightForAge
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return boolean
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set month
     *
     * @param string $month
     *
     * @return WeightForAge
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string
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
     * @return WeightForAge
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
     * @return WeightForAge
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
     * @return WeightForAge
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
     * @return WeightForAge
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
     * @return WeightForAge
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
     * @return WeightForAge
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
     * Set p2sd
     *
     * @param string $p2sd
     *
     * @return WeightForAge
     */
    public function setP2sd($p2sd)
    {
        $this->p2sd = $p2sd;

        return $this;
    }

    /**
     * Get p2sd
     *
     * @return string
     */
    public function getP2sd()
    {
        return $this->p2sd;
    }

    /**
     * Set p3sd
     *
     * @param string $p3sd
     *
     * @return WeightForAge
     */
    public function setP3sd($p3sd)
    {
        $this->p3sd = $p3sd;

        return $this;
    }

    /**
     * Get p3sd
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
