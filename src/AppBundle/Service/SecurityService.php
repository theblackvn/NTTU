<?php

namespace AppBundle\Service;

use AMZ\BackendBundle\Service\DBQueryService;
use AMZ\BackendBundle\Service\ValidateService;
use AMZ\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class SecurityService
{
    private $queryService;
    private $tokenStorage;
    private $encoderFactory;
    private $validator;

    public function __construct(DBQueryService $queryService, TokenStorage $tokenStorage, ValidateService $validator)
    {
        $this->queryService = $queryService;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
    }

    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function changePassword($data)
    {
        if (empty(trim($data['old-pw'])) || empty(trim($data['new-pw'])) || empty(trim($data['confirm-pw']))) {
            return array(
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            );
        }
        if (trim($data['new-pw']) != trim($data['confirm-pw'])) {
            return array(
                'success' => false,
                'message' => 'Mật khẩu mới không trùng khớp'
            );
        }
        $user = $this->tokenStorage->getToken()->getUser();
        $encoder = $this->encoderFactory->getEncoder($user);
        $encodePw = $encoder->encodePassword(trim($data['old-pw']), $user->getSalt());
        if ($user->getPassword() != $encodePw) {
            return array(
                'success' => false,
                'message' => 'Mật khẩu cũ không đúng'
            );
        }
        $user->setPassword(trim($data['new-pw']));
        $result = $this->queryService->update($user);
        if (false !== $result) {
            return array(
                'success' => true,
                'message' => 'Đã thay đổi'
            );
        } else {
            return array(
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
            );
        }
    }

    public function register($parameters)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }

        if (empty($data['fullName']) || empty($data['username']) ||
            empty($data['password']) || empty($data['confirm-password']) || empty($data['email'])
        ) {
            return array(
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            );
        }
        $count = $this->queryService
            ->getRepository('AMZUserBundle:User')
            ->total(array(
                'username' => $data['username']
            ));
        if (0 < $count) {
            return array(
                'success' => false,
                'message' => 'Tên đăng nhập đã tồn tại'
            );
        }
        if ($data['password'] != $data['confirm-password']) {
            return array(
                'success' => false,
                'message' => 'Mật khẩu không trùng khớp'
            );
        }
        $result = $this->validator->email($data['email']);
        if (!$result) {
            return array(
                'success' => false,
                'message' => 'Email không hợp lệ'
            );
        }
        $count = $this->queryService
            ->getRepository('AMZUserBundle:User')
            ->total(array(
                'email' => $data['email']
            ));
        if (0 < $count) {
            return array(
                'success' => false,
                'message' => 'Email đã tồn tại'
            );
        }

        $user = new User();
        $user->setFullName($data['fullName']);
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email']);
        $user->setRole('ROLE_USER');
        $result = $this->queryService->getRepository('AMZUserBundle:User')
            ->insert($user);

        if (false !== $result) {
            return array(
                'success' => true,
                'user' => $user
            );
        } else {
            return array(
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
            );
        }
    }
}