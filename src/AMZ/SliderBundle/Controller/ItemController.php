<?php

namespace AMZ\SliderBundle\Controller;

use AMZ\SliderBundle\Entity\Item;
use AMZ\SliderBundle\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ItemController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
            ->paging($parameters, $request->get('page', 1), Item::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZSliderBundle:Item:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Item();
        $form = $this->createForm(ItemType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_slider_item_homepage');
        }
        return $this->render('AMZSliderBundle:Item:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(ItemType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_slider_item_homepage');
        }

        return $this->render('AMZSliderBundle:Item:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_slider_item_homepage');
    }
}
