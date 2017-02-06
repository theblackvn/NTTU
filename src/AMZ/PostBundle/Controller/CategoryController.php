<?php

namespace AMZ\PostBundle\Controller;

use AMZ\BackendBundle\Adapter\DBQueryAdapter;
use AMZ\PostBundle\Entity\Category;
use AMZ\PostBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function indexAction(Request $request)
    {

        $parameters = $request->query->all();
        $parameters['level']=1;
        //echo "<pre>";var_dump($parameters);die();
        //$this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
         //   ->grt(array('level'=>1));
        $pagination = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->paging($parameters, $request->get('page', 1), Category::ADMIN_NUMBER_ITEM_PER_PAGE);

        /*$aChilds = array();
        if (!empty($pagination)) {
            foreach ($pagination as $catLevel1) {
                $childLevel2 = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
                    ->get(array('level'=>2,'parent'=>$catLevel1->getId()));
                if (!empty($childLevel2)) {
                    $aChilds[$catLevel1->getId()] = array();
                }
            }

        }
        */
        return $this->render('AMZPostBundle:Category:index.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function createAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createForm(CategoryType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //echo "<pre>";
            //var_dump($request->get('amz_post_category')['parent']);die();
            if (empty($entity->getParent()) && !empty($request->get('amz_post_category')['parent'])) {
                $parent = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')->find($request->get('amz_post_category')['parent']);
                $entity->setParent($parent);
                $entity->setLevel($parent->getLevel()+1);
            }
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
                ->insert($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_category_homepage');
        }
        $category = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
           ->get(array('level'=>1));
        return $this->render('AMZPostBundle:Category:create.html.twig', array(
            'form' => $form->createView(),
            'category' => $category,
            'entity' => $entity
        ));
    }

    public function editAction($id, Request $request)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $form = $this->createForm(CategoryType::class, $entity, array('current_id' => $id));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($request->get('amz_post_category')['parent'])) {
                $parent = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')->find($request->get('amz_post_category')['parent']);
                $entity->setParent($parent);
                $entity->setLevel($parent->getLevel()+1);
            }
            //var_dump($parent->getTitle());die();
            $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
                ->update($entity);
            if ($result) {
                $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được lưu thành công!'));
            } else {
                $this->addFlash('error', $this->get('translator')->trans('Lưu dữ liệu thất bại! Vui lòng thử lại sau'));
            }
            return $this->redirectToRoute('amz_post_category_homepage');
        }
        $category = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->get(array('level'=>1));
        return $this->render('AMZPostBundle:Category:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'category' => $category
        ));
    }

    public function deleteAction($id)
    {
        $entity = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->find($id);
        if (empty($entity)) {
            throw $this->createNotFoundException('Not found entity: ' . $id);
        }
        $result = $this->get('amz_db.service.query')->getRepository('AMZPostBundle:Category')
            ->remove($entity);
        if ($result) {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        } else {
            $this->addFlash('error', $this->get('translator')->trans('Xóa dữ liệu thất bại! Vui lòng thử lại sau'));
        }
        return $this->redirectToRoute('amz_post_category_homepage');
    }
}
