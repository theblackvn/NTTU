<?php

namespace AMZ\UserBundle\Controller;

use AMZ\UserBundle\Entity\User;
use AMZ\UserBundle\Form\ChangePasswordType;
use AMZ\UserBundle\Form\CreatePersonalUserType;
use AMZ\UserBundle\Form\EditPersonalUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonalUserController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $parameters['role'] = User::ROLE_USER;
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')
            ->paging($parameters, $request->get('page', 1), User::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZUserBundle:PersonalUser:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new User();
        $entity->setRole(User::ROLE_USER);
        $form = $this->createForm(CreatePersonalUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_personal_user_index');
        }

        return $this->render('AMZUserBundle:PersonalUser:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->findOneBy(array(
            'role' => User::ROLE_USER,
            'id' => $id
        ));
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(EditPersonalUserType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_personal_user_index');
        }

        return $this->render('AMZUserBundle:PersonalUser:edit.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZUserBundle:User')->findOneBy(array(
            'role' => User::ROLE_USER,
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
        return $this->redirectToRoute('amz_personal_user_index');
    }
}