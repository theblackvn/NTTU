<?php

namespace AppBundle\Twig;

use AMZ\BackendBundle\Service\DBQueryService;
use Symfony\Component\HttpFoundation\Session\Session;

class AMZApplicationExtension extends \Twig_Extension
{
    private $session;
    private $queryService;

    public function __construct(Session $session, DBQueryService $queryService)
    {
        $this->session = $session;
        $this->queryService = $queryService;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('current_school', array($this, 'currentSchool'))
        );
    }

    public function currentSchool()
    {
        $schoolId = $this->session->get('school');
        if (empty($schoolId)) {
            return null;
        }
        $school = $this->queryService->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        return $school;
    }

    public function getName()
    {
        return 'amz_application_extension';
    }
}