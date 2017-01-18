<?php

namespace AMZ\BackendBundle\Twig;

use AMZ\BackendBundle\Service\DBQueryService;

class OptionExtension extends \Twig_Extension
{
    private $service;

    public function __construct(DBQueryService $service)
    {
        $this->service = $service;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('amz_configuration_value', array($this, 'configurationValue'))
        );
    }

    public function configurationValue($name)
    {
        $entity = $this->service->getRepository('AMZBackendBundle:Option')
            ->findOneBy(array(
            'name' => $name
        ));
        if (empty($entity)) {
            return null;
        }
        return $entity->getValue();
    }

    public function getName()
    {
        return 'amz_configuration_extension';
    }
}