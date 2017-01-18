<?php

namespace AMZ\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class SEO
{
    /**
     * @return mixed
     */
    public function getFacebookThumbnail()
    {
        return $this->facebookThumbnail;
    }

    /**
     * @param mixed $facebookThumbnail
     */
    public function setFacebookThumbnail($facebookThumbnail)
    {
        $this->facebookThumbnail = $facebookThumbnail;
    }

    /**
     * @return mixed
     */
    public function getFacebookDescription()
    {
        return $this->facebookDescription;
    }

    /**
     * @param mixed $facebookDescription
     */
    public function setFacebookDescription($facebookDescription)
    {
        $this->facebookDescription = $facebookDescription;
    }

    /**
     * @return mixed
     */
    public function getFacebookTitle()
    {
        return $this->facebookTitle;
    }

    /**
     * @param mixed $facebookTitle
     */
    public function setFacebookTitle($facebookTitle)
    {
        $this->facebookTitle = $facebookTitle;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param mixed $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return mixed
     */
    public function getMetaKeyword()
    {
        return $this->metaKeyword;
    }

    /**
     * @param mixed $metaKeyword
     */
    public function setMetaKeyword($metaKeyword)
    {
        $this->metaKeyword = $metaKeyword;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaKeyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $facebookDescription;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookThumbnail;
}