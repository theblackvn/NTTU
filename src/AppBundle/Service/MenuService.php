<?php

namespace AppBundle\Service;

use AMZ\BackendBundle\Service\DBQueryService;
use AMZ\BackendBundle\Service\ValidateService;
use AMZ\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class MenuService
{
    private $queryService;
    private $tokenStorage;
    private $encoderFactory;
    private $validator;

    public function __construct(DBQueryService $queryService, TokenStorage $tokenStorage)
    {
        $this->queryService = $queryService;
        $this->tokenStorage = $tokenStorage;
    }

    public function getMenu() {
        $menuDaoTao = $this->queryService
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'dao-tao'
            ));
        $menuTuyenSinh = $this->queryService
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'tuyen-sinh'
            ));
        $menuNghienCuu = $this->queryService
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'nghien-cuu'
            ));
        $menuGioiThieu = $this->queryService
            ->getRepository('AMZPostBundle:Category')
            ->findOneBy(array(
                'isFeature' => 1,
                'slug' => 'gioi-thieu-chung'
            ));
        return array('menuDaoTao' => $menuDaoTao,
                    'menuTuyenSinh' => $menuTuyenSinh,
                    'menuNghienCuu' => $menuNghienCuu,
                    'menuGioiThieu' => $menuGioiThieu);
    }
}