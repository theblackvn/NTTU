<?php

namespace AppBundle\Controller;

use AMZ\PostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller
{
    public function loginAction()
    {
        return $this->render('TwigBundle:Exception:error403.html.twig');
    }

    public function changePasswordAction(Request $request)
    {
        $res = new Response();
        $data = $request->request->all();
        $result = $this->get('application.service.security')
            ->changePassword($data);
        if (!$result['success']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => $result['message'])));
            return $res;
        } else {
            $res->setStatusCode(200);
            return $res;
        }
    }

    public function registerAction(Request $request)
    {
        $res = new Response();
        $data = $request->request->all();
        $result = $this->get('application.service.security')
            ->register($data);
        if (!$result['success']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => $result['message'])));
            return $res;
        } else {
            $user = $result['user'];
            $token = new UsernamePasswordToken($user, null, 'frontend', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_frontend', serialize($token));
            $res->setStatusCode(200);
            $res->setContent(json_encode(array(
                'message' => 'Tài khoản được đăng ký thành công',
                'redirect_url' => $this->generateUrl('application_profile_index')
            )));
            return $res;
        }
    }
}
