<?php

namespace AMZ\PostBundle\Twig;

use AMZ\BackendBundle\Service\DBQueryService;
use AMZ\PostBundle\Entity\Post;

class PostExtension extends \Twig_Extension
{
    private $service;

    public function __construct(DBQueryService $service)
    {
        $this->service = $service;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('amz_static_block', array($this, 'staticBlock'))
        );
    }

    public function staticBlock($name)
    {
        $entity = $this->service
            ->getRepository('AMZPostBundle:Post')
            ->findOneBy(array(
                'title' => $name,
                'type' => Post::TYPE_STATIC_BLOCK
            ));
        if (empty($entity)) {
            return null;
        }
        return $entity->getContent();
    }

    public function getName()
    {
        return 'amz_post_extension';
    }
}