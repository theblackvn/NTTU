<?php

namespace AMZ\ProfileBundle\Twig;

use AMZ\BackendBundle\Service\DBQueryService;
use AMZ\PostBundle\Entity\Post;

class SchoolExtension extends \Twig_Extension
{
    private $service;

    public function __construct(DBQueryService $service)
    {
        $this->service = $service;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_school_years', array($this, 'getSchoolYears')),
            new \Twig_SimpleFunction('get_school_units', array($this, 'getSchoolUnits')),
        );
    }

    public function getSchoolYears()
    {
        $entities = $this->service
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->get(array(), array(
                'name' => 'DESC'
            ));
        return $entities;
    }

    public function getSchoolUnits()
    {
        $entities = $this->service
            ->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->get(array(), array(
                'name' => 'ASC'
            ));
        return $entities;
    }

    public function getName()
    {
        return 'amz_post_extension';
    }
}