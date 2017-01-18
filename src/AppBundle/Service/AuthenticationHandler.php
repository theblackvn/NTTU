<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;
    private $httpUtils;

    public function __construct(HttpUtils $httpUtils, Router $router)
    {
        $this->httpUtils = $httpUtils;
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        $roles = $user->getRoles();
        if ($request->isXmlHttpRequest()) {
            $res = new Response();
            if (in_array('ROLE_USER', $roles)) {
                $redirectUrl = $this->router->generate('application_profile_index');
            } elseif (in_array('ROLE_MEDICAL', $roles)) {
                $redirectUrl = $this->router->generate('application_profile_index');
            } elseif (in_array('ROLE_PRINCIPAL', $roles)) {
                $redirectUrl = $this->router->generate('application_for_school_index');
            } else {
                $redirectUrl = $this->router->generate('application_homepage');
            }
            $res->setContent($redirectUrl);
            $res->setStatusCode(200);
            return $res;
        } else {
            $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
//        echo 'aaa';die();
        if ($request->isXmlHttpRequest()) {
            $res = new Response();
            $res->setContent($exception->getMessage());
            $res->setStatusCode(403);
            return $res;
        } else {
            $this->httpUtils->createRedirectResponse($request, $this->determineTargetUrl($request));
        }
    }
}