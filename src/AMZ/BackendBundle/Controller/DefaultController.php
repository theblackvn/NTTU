<?php

namespace AMZ\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AMZBackendBundle:Default:index.html.twig');
    }
}
