<?php

namespace AMZ\SliderBundle\Controller;

use AMZ\SliderBundle\Entity\Media;
use AMZ\SliderBundle\Form\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends Controller
{
    public function indexAction(Request $request)
    {
        $parameters = $request->query->all();
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Media')
            ->paging($parameters, $request->get('page', 1), Media::ADMIN_NUMBER_ITEM_PER_PAGE);
        return $this->render('AMZSliderBundle:Media:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Media();
        $form = $this->createForm(MediaType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Item')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_media_homepage');
        }
        return $this->render('AMZSliderBundle:Media:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Media')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(MediaType::class, $entity);
        $form->handleRequest($request);
        //var_dump("123");die();

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Media')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_media_homepage');
        }
        //var_dump("123");die();

        return $this->render('AMZSliderBundle:Media:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Media')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZSliderBundle:Media')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_media_homepage');
    }
}
