<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserProfileController extends Controller
{
    public function indexAction()
    {
        die();
        return $this->render('AppBundle:UserProfile:index.html.twig');
    }

    public function ZeroYOAction()
    {
        $result = array();
        $cities =  $this->getParameter('city');
        return $this->render('AppBundle:UserProfile:fiveYO.html.twig', array(
            'result' => $result,
            'cities' => $cities,
            'category' => 1
            ));
    }

    public function fiveYOAction()
    {
        $result = array();
        $cities =  $this->getParameter('city');
        return $this->render('AppBundle:UserProfile:fiveYO.html.twig', array(
                                'result' => $result,
                                'cities' => $cities,
                                'category' => 2));
    }

    public function over19YOAction()
    {
        $result = array();
        $cities =  $this->getParameter('city');
        return $this->render('AppBundle:UserProfile:fiveYO.html.twig', array(
            'result' => $result,
            'cities' => $cities,
            'category' => 3));
    }

    public function fiveYOResultAction()
    {
        $session = new Session();
        $result = array();
        if (isset($_POST)) {
            $result = $this->get('amz.service.bmi')->userProfileCalculate($_POST);
            //echo "<pre>";var_dump($result);die();
            //Set session for printing
            if (!empty($result)) {
                foreach ($result as $key=>$value) {
                    $session->set($key, $value);
                }
            }
            if ($_POST['category'] > 1) $session->set('calculatedBmi', $_POST['bmi']);
            $session->set('category', $_POST['category']);
        }
        $isValidAge = true;
        if (($_POST['category'] == 1 && $_POST['realAge'] > 5)
        || ($_POST['category'] == 2 && ($_POST['realAge'] > 19 || $_POST['realAge'] < 5))
        || ($_POST['category'] == 3 && $_POST['realAge'] < 19 ))
            $isValidAge = false;
        return $this->render('AppBundle:UserProfile:fiveYOResult.html.twig', array(
            'result' => $result,
            'category' => $_POST['category'],
            'isValidAge' => $isValidAge,
            'calculatedBmi' => $_POST['bmi']));
    }

    public function printResultAction()
    {
        $session = new Session();
        return $this->render('AppBundle:UserProfile:printResult.html.twig', array(
            'result' => $session,
        ));
    }

    public function saveAdviseAction() {
        try {
            $session = new Session();
            $session->set('advise', $_POST['advise']);
            /*
            $result = $this->get('amz_db.service.query')
                ->getRepository('AMZProfileBundle:ProfileBmiResult')
                ->find($_POST['id']);
            if (!empty($result)) {
                $result->setAdvise($_POST['advise']);
                //var_dump($result);die();
                $this->get('amz_db.service.query')
                    ->getRepository('AMZProfileBundle:ProfileBmiResult')
                    ->update($result);
            }
            */
            return new JsonResponse(array('result' => 'ok'));
        } catch(\Exception $e){
            //var_dump($e->getMessage());die();
            return new JsonResponse(array('result' => 'error'));
        }

    }

}
