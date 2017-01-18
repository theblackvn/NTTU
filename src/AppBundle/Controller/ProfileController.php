<?php

namespace AppBundle\Controller;

use AMZ\UserBundle\Entity\UserProfile;
use AppBundle\Form\PersonalUserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ProfileController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:Profile:index.html.twig');
    }

    public function personalListAction($groupName)
    {
        if ($groupName == UserProfile::GROUP_1_SLUG) {
            $group = UserProfile::GROUP_1;
        } elseif ($groupName == UserProfile::GROUP_2_SLUG) {
            $group = UserProfile::GROUP_2;
        } else {
            $group = UserProfile::GROUP_3;
        }
        $profiles = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfile')
            ->get(array(
                'group' => $group,
                'user' => $this->getUser()->getId()
            ));
        return $this->render('AppBundle:Profile:personal-list.html.twig', array(
            'groupName' => $groupName,
            'group' => $group,
            'profiles' => $profiles
        ));
    }

    public function createPersonAction(Request $request)
    {
        $profile = new UserProfile();
        $profile->setUser($this->getUser());
        $form = $this->createForm(PersonalUserProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileAvatar = $profile->getFile();
            if (!empty($fileAvatar)) {
                $fileName = md5(uniqid()) . '.' . $fileAvatar->guessExtension();
                $fileAvatar->move(
                    $this->getParameter('profile_avatar_directory'),
                    $fileName
                );
                $profile->setAvatar($this->getParameter('full_avatar_url') . $fileName);
            }
            $result = $this->get('amz_db.service.query')
                ->getRepository('AMZUserBundle:UserProfile')
                ->insert($profile);
            if (false !== $result) {
                $this->addFlash(
                    'notice',
                    'Hồ sơ đã được tạo thành công'
                );
            } else {
                $this->addFlash(
                    'error',
                    'Có lỗi xảy ra. Vui lòng thử lại sau'
                );
            }
            return $this->redirectToRoute('application_profile_create_person');
        }
        return $this->render('AppBundle:Profile:create-person.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function personalDetailAction($id, Request $request)
    {
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfile')
            ->findOneBy(array(
                'id' => $id,
                'user' => $this->getUser()->getId()
            ));
        if (empty($profile)) {
            throw $this->createNotFoundException();
        }
        $profileHistory = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfileBmiResult')
            ->get(array(
                'profile' => $profile->getId()
            ));
        $form = $this->createForm(PersonalUserProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileAvatar = $profile->getFile();
            if (!empty($fileAvatar)) {
                $fileName = md5(uniqid()) . '.' . $fileAvatar->guessExtension();
                $fileAvatar->move(
                    $this->getParameter('profile_avatar_directory'),
                    $fileName
                );
                $profile->setAvatar($this->getParameter('full_avatar_url') . $fileName);
            }
            $result = $this->get('amz_db.service.query')
                ->getRepository('AMZUserBundle:UserProfile')
                ->update($profile);
            if (false !== $result) {
                $this->addFlash(
                    'notice',
                    'Hồ sơ đã được thay đổi thành công'
                );
            } else {
                $this->addFlash(
                    'error',
                    'Có lỗi xảy ra. Vui lòng thử lại sau'
                );
            }
            return $this->redirectToRoute('application_profile_personal_detail', array('id' => $id));
        }
        return $this->render('AppBundle:Profile:personal-detail.html.twig', array(
            'profile' => $profile,
            'profileHistory' => $profileHistory,
            'form' => $form->createView()
        ));
    }

    public function personalHistoryAction($id, $historyId)
    {
        $history = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfileBmiResult')
            ->findOneBy(array(
                'id' => $historyId,
            ));
        if (empty($history)) {
            throw $this->createNotFoundException();
        }
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfile')
            ->findOneBy(array(
                'id' => $id,
                'user' => $this->getUser()->getId()
            ));

//        Hoàng Lê: 14 Dec, 2016 11:15 - add function to calculate month
        $measureDate = $history->getMeasureDate();
        $measureDate = ($measureDate == null)? (new \DateTime()):$measureDate;
        $month = $this->get('amz.service.bmi')->getMonthNo($profile->getDateOfBirth(), $measureDate);
        return $this->render('AppBundle:Profile:personal-history-detail.html.twig', array(
            'result' => $history,
            'category' => $history->getCategory(),
            'profile' => $profile,
            'month' => $month
        ));
    }

    public function personalHistoryPrintAction($id, $historyId)
    {
        $history = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfileBmiResult')
            ->findOneBy(array(
                'id' => $historyId,
            ));
        if (empty($history)) {
            throw $this->createNotFoundException();
        }
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfile')
            ->findOneBy(array(
                'id' => $id,
                'user' => $this->getUser()->getId()
            ));

        //        Hoàng Lê: 20 Dec, 2016 06:57 - add function to calculate month
        $measureDate = $history->getMeasureDate();
        $measureDate = ($measureDate == null)? (new \DateTime()):$measureDate;
        $month = $this->get('amz.service.bmi')->getMonthNo($profile->getDateOfBirth(), $measureDate);

        return $this->render('AppBundle:Profile:personal-history-print.html.twig', array(
            'result' => $history,
            'category' => $history->getCategory(),
            'profile' => $profile,
            'month' => $month
        ));
    }
    public function bmiCalculateAction()
    {
        $profiles = $this->get('amz_db.service.query')
            ->getRepository('AMZUserBundle:UserProfile')
            ->get(array(
                'user' => $this->getUser()->getId()
            ));
        return $this->render('AppBundle:Profile:personal-bmi-calculate.html.twig', array(
            'profiles' => $profiles
        ));
    }
    public function bmiCalculateResultAction()
    {
        $session = new Session();
        //$session->start();
        $result = array('category'=>1);
        if (isset($_POST)) {
            $result = $this->get('amz.service.bmi')->_calculate($_POST['profileId'], $_POST['weight'], $_POST['height']);
            //echo "<pre>";var_dump($result);die();
            //Set session for printing
            if (!empty($result)) {
                foreach ($result as $key=>$value) {
                    $session->set($key, $value);
                }
                $session->set('category', $result['category']);
            }

        }
        //echo "<pre>";var_dump($result);die();
        return $this->render('AppBundle:Profile:personal-bmi-calculate-result.html.twig', array(
            'result' => $result,
            'id' => $_POST['profileId'],
            'category' => $result['category']));
    }

}
