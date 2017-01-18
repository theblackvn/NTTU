<?php

namespace AMZ\UserBundle\Controller;

use AMZ\UserBundle\Entity\User;
use AMZ\UserBundle\Form\ChangePasswordType;
use AMZ\UserBundle\Form\CreateUserType;
use AMZ\UserBundle\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $parameters['role'] = User::ROLE_ADMIN;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')
            ->paging($parameters, $request->get('page', 1), User::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZUserBundle:User:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new User();
        $entity->setRole(User::ROLE_ADMIN);
        $form = $this->createForm(CreateUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_user_index');
        }

        return $this->render('AMZUserBundle:User:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->findOneBy(array(
            'role' => User::ROLE_ADMIN,
            'id' => $id
        ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(EditUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_user_index');
        }

        return $this->render('AMZUserBundle:User:edit.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    public function changeProfileAction(Request $request)
    {
        $entity = $this->getUser();
        $form = $this->createForm(EditUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_user_changeProfile');
        }

        return $this->render('AMZUserBundle:User:change-profile.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    public function changePasswordAction(Request $request)
    {
        $entity = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_user_changePassword');
        }

        return $this->render('AMZUserBundle:User:change-password.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->findOneBy(array(
            'role' => User::ROLE_ADMIN,
            'id' => $id
        ));
        if (empty($entity)) {
            $this->createNotFoundException('Không tìm thấy dữ liệu');
        }
        $entity->setDeleted(true);
        $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->update($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_user_index');
    }
}