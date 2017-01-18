<?php

namespace AMZ\UserBundle\Controller;

use AMZ\UserBundle\Entity\User;
use AMZ\UserBundle\Form\ChangePasswordType;
use AMZ\UserBundle\Form\CreateSchoolUserType;
use AMZ\UserBundle\Form\EditSchoolUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SchoolUserController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $parameters['role'] = User::ROLE_PRINCIPAL;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')
            ->paging($parameters, $request->get('page', 1), User::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZUserBundle:SchoolUser:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new User();
        $entity->setRole(User::ROLE_PRINCIPAL);
        $form = $this->createForm(CreateSchoolUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_school_user_index');
        }

        return $this->render('AMZUserBundle:SchoolUser:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->findOneBy(array(
            'role' => User::ROLE_PRINCIPAL,
            'id' => $id
        ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(EditSchoolUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_school_user_index');
        }

        return $this->render('AMZUserBundle:SchoolUser:edit.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->findOneBy(array(
            'role' => User::ROLE_PRINCIPAL,
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
        return $this->redirectToRoute('amz_school_user_index');
    }
}