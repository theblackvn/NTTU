<?php

namespace AppBundle\Controller;

use AMZ\ProfileBundle\Entity\Profile;
use AMZ\ProfileBundle\Entity\SchoolClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    public function ajaxLoadClassByYearAction(Request $request)
    {
        $res = new Response();
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            $res->setStatusCode(404);
            return $res;
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            $res->setStatusCode(404);
            return $res;
        }
        $schoolYearId = $request->get('schoolYear', null);
        if (empty($schoolYearId)) {
            $res->setStatusCode(404);
            return $res;
        }
        $classes = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->get(array(
                'school_year' => $schoolYearId,
                'school' => $school->getId()
            ));
        $html = $this->renderView('@App/School/_ajax-load-classes-by-school-year.html.twig', array('classes' => $classes));
        $res->setContent($html);
        $res->getStatusCode(200);
        return $res;
    }

    public function importHeightWeightToProfileStep1Action(Request $request)
    {
        try {
            $res = new Response();
            $data = $request->request->all();
            $file = $request->files->get('excel-file');
            $result = $this->get('application.service.profile')
                ->importHeightWeightToProfileStep1($data, $file);
            if (!$result['success']) {
                $res->setStatusCode(400);
                $res->setContent(json_encode(array('message' => $result['message'])));
                return $res;
            } else {
                $html = $this->renderView('@App/School/_ajax-load-data-from-excel-import-height-weight.html.twig', array('data' => $result['data']));
                $res->setContent($html);
                $res->setStatusCode(200);
                return $res;
            }
        } catch (\Exception $e) {
            $res = new Response();
            $res->setStatusCode(500);
            $res->setContent(json_encode(array('message' => $e->getMessage())));
            return $res;
        }
        $res = new Response();
        $res->setStatusCode(500);
        return $res;
    }

    public function importProfileToClassStep1Action(Request $request)
    {
        try {
            $res = new Response();
            $data = $request->request->all();
            $file = $request->files->get('excel-file');
            $result = $this->get('application.service.profile')
                ->importProfileToClassStep1($data, $file);
            if (!$result['success']) {
                $res->setStatusCode(400);
                $res->setContent(json_encode(array('message' => $result['message'])));
                return $res;
            } else {
                $html = $this->renderView('@App/School/_ajax-load-data-from-excel-import-student.html.twig', array('data' => $result['data']));
                $res->setContent($html);
                $res->setStatusCode(200);
                return $res;
            }
        } catch (\Exception $e) {
            $res = new Response();
            $res->setStatusCode(500);
            $res->setContent(json_encode(array('message' => $e->getMessage())));
            return $res;
        }
        $res = new Response();
        $res->setStatusCode(500);
        return $res;
    }

    public function importProfileToClassStepCompleteAction()
    {
        $data = $this->get('application.service.profile')
            ->insertDataImportProfileToClass();
        if (0 == $data['nbrInserted']) {
            $this->addFlash('error-no-item-imported', $this->get('translator')->trans('Không có dữ liệu nào được cập nhật'));
        } else {
            $this->addFlash('notice', "Có {$data['nbrInserted']} dòng dữ liệu được cập nhật");
        }

        return $this->redirectToRoute('application_for_school_studentInClass', array('id' => $data['schoolClass']->getId()));
    }

    public function importHeightWeightToProfileStepCompleteAction()
    {
        $data = $this->get('application.service.profile')
            ->insertDataImportHeightWeightToProfile();
        if (0 == $data['nbrInserted']) {
            $this->addFlash('error-no-item-imported', $this->get('translator')->trans('Không có dữ liệu nào được cập nhật'));
        } else {
            $this->addFlash('notice', "Có {$data['nbrInserted']} dòng dữ liệu được cập nhật");
        }

        return $this->redirectToRoute('application_for_school_index');
    }

    public function indexAction(Request $request)
    {
        if ('POST' == strtoupper($request->getMethod())) {
            $school = $request->get('school');
            $this->get('session')
                ->set('school', $school);
            return $this->redirectToRoute('application_for_school_heightWeightList');
        }
        $user = $this->getUser();
        $schools = $user->getWorkSchools();
        if (empty($schools) || $schools->isEmpty()) {
            return $this->render('AppBundle:School:empty-school.html.twig');
        } else {
            $this->get('session')
                ->set('school', $schools[0]->getId());
            return $this->redirectToRoute('application_for_school_heightWeightList');
        }
    }

    public function heightWeightListAction(Request $request)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }

        $parameters = $request->query->all();
        $parameters['school'] = $school->getId();
        $parameters['has_bmi'] = 'yes';
        $pagination = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->paging($parameters, $request->get('page', 1), 20);
        $this->get('session')
            ->set('pagination', $pagination);
        return $this->render('AppBundle:School:height-weight-list.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function exportAction(Request $request)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $parameters = $request->query->all();

        $parameters['school'] = $school->getId();
        $parameters['has_bmi'] = 'yes';

        $students = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')->get($parameters);

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);

        $sheet = $excelAdapter->getActiveSheet();
        $sheet->getStyle("A1:I1")
            ->getFont()->getColor()->setRGB("ffffff");
        $sheet->getStyle("A1:I1")
            ->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1E83CD');

        for ($i = 0; $i < 9; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        $excelAdapter->setCellValueByColumnAndRow(0, 1, 'Mã HS');
        $excelAdapter->setCellValueByColumnAndRow(1, 1, 'Tên HS');
        $excelAdapter->setCellValueByColumnAndRow(2, 1, 'Ngày sinh');
        $excelAdapter->setCellValueByColumnAndRow(3, 1, 'Giới tính');
        $excelAdapter->setCellValueByColumnAndRow(4, 1, 'Chiều cao (cm)');
        $excelAdapter->setCellValueByColumnAndRow(5, 1, 'Cân nặng (kg)');
        $excelAdapter->setCellValueByColumnAndRow(6, 1, 'BMI');
        $excelAdapter->setCellValueByColumnAndRow(7, 1, 'Kết quả');
        $excelAdapter->setCellValueByColumnAndRow(8, 1, 'Ngày đo (dd/mm/YYYY)');
//        $profiles = $class->getProfiles();
        if (!empty($students)) {
            $i = 2;
            foreach ($students as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getProfileId());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getName());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, $item->getDateOfBirth());

                $iGender = $item->getGender();
                $gender = ($iGender == Profile::GENDER_MALE) ? "Nam" : "Nữ";
                $excelAdapter->setCellValueByColumnAndRow(3, $i, $gender);
                $excelAdapter->setCellValueByColumnAndRow(4, $i, $item->getLastHeight());
                $excelAdapter->setCellValueByColumnAndRow(5, $i, $item->getLastWeight());
                $excelAdapter->setCellValueByColumnAndRow(6, $i, round($item->getLastBMI(), 1));
                $excelAdapter->setCellValueByColumnAndRow(7, $i, $item->getLastResult());
                $excelAdapter->setCellValueByColumnAndRow(8, $i, $item->getLastDayWeight());
                $i++;
            }
        }
        return $excelAdapter->export('danh-sach-can-do-' . time() . '.xls');

    }

    public function heightWeightHistoryAction($profileId, Request $request)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }

        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->findOneBy(array(
                'profile_id' => $profileId,
                'school' => $school->getId(),
                'has_bmi' => 'yes'
            ));
        if (empty($profile)) {
            throw $this->createNotFoundException();
        }

        return $this->render('AppBundle:School:height-weight-history.html.twig', array(
            'profile' => $profile
        ));
    }

    public function classListAction(Request $request)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }

        $parameters = $request->query->all();
        $parameters['school'] = $school->getId();
        $pagination = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->paging($parameters, $request->get('page', 1), 20);
        return $this->render('AppBundle:School:class-list.html.twig', array(
            'pagination' => $pagination,
            'parameters' => $parameters
        ));
    }

    public function addClassAction(Request $request)
    {
        $res = new Response();
        $data = $request->request->all();
        $result = $this->get('application.service.profile')
            ->addClass($data);
        if (!$result['success']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => $result['message'])));
            return $res;
        } else {
            $res->setStatusCode(200);
            $res->setContent(json_encode(array('message' => $result['message'], 'redirect_url' => $this->generateUrl('application_for_school_classList'))));
            return $res;
        }
    }

    public function studentInClassAction($id)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array('id' => $id, 'school' => $school->getId()));
        if (empty($class)) {
            throw $this->createNotFoundException();
        }
        return $this->render('@App/School/student-in-class.html.twig', array(
            'class' => $class
        ));
    }

    public function deleteStudentInClassAction($id, $profileId)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array('id' => $id, 'school' => $school->getId()));
        if (empty($class)) {
            throw $this->createNotFoundException();
        }
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->findOneBy(array('profile_id' => $profileId));
        if (empty($class)) {
            throw $this->createNotFoundException();
        }
        $class->removeProfile($profile);
        $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->update($class);
        $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        return $this->redirectToRoute('application_for_school_studentInClass', array('id' => $id));
    }

    public function deleteClassAction($id)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array(
                'id' => $id,
                'school' => $school->getId()
            ));
        if (empty($class)) {
            throw $this->createNotFoundException();
        }
        $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->remove($class);
        $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được xóa thành công!'));
        return $this->redirectToRoute('application_for_school_classList');
    }

    public function exportHeightWeightStandardFileAction($id)
    {
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return $this->redirectToRoute('application_for_school_index');
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array(
                'id' => $id,
                'school' => $school->getId()
            ));
        if (empty($class)) {
            throw $this->createNotFoundException();
        }

        $excelAdapter = $this->get('application.excel.export');
        $excelAdapter->setActiveSheet(0);
        $excelAdapter->setCellValueByColumnAndRow(0, 1, 'Mã HS');
        $excelAdapter->setCellValueByColumnAndRow(1, 1, 'Tên HS');
        $excelAdapter->setCellValueByColumnAndRow(2, 1, 'Chiều cao (cm)');
        $excelAdapter->setCellValueByColumnAndRow(3, 1, 'Cân nặng (kg)');
        $excelAdapter->setCellValueByColumnAndRow(4, 1, 'Ngày đo (dd/mm/YYYY)');
        $profiles = $class->getProfiles();
        if (!empty($profiles)) {
            $i = 2;
            foreach ($profiles as $item) {
                $excelAdapter->setCellValueByColumnAndRow(0, $i, $item->getProfileId());
                $excelAdapter->setCellValueByColumnAndRow(1, $i, $item->getName());
                $excelAdapter->setCellValueByColumnAndRow(2, $i, '');
                $excelAdapter->setCellValueByColumnAndRow(3, $i, '');
                $excelAdapter->setCellValueByColumnAndRow(4, $i, '');
                $i++;
            }
        }
        return $excelAdapter->export('danh-sach-chuan-can-do-lop' . $class->getCode() . '.xls');
    }

    public function editClassAction($id)
    {
        $res = new Response();
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            $res->setStatusCode(404);
            return $res;
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            $res->setStatusCode(404);
            return $res;
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array(
                'id' => $id,
                'school' => $school->getId()
            ));
        if (empty($class)) {
            $res->setStatusCode(404);
            return $res;
        }
        $html = $this->renderView('@App/School/_form-edit-class.html.twig', array('class' => $class));
        $res->setContent($html);
        $res->setStatusCode(200);
        return $res;
    }

    public function doEditClassAction($id, Request $request)
    {
        $res = new Response();
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            $res->setStatusCode(404);
            return $res;
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            $res->setStatusCode(404);
            return $res;
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array(
                'id' => $id,
                'school' => $school->getId()
            ));
        if (empty($class)) {
            $res->setStatusCode(404);
            return $res;
        }
        $data = $request->request->all();
        $result = $this->get('application.service.profile')
            ->editClass($class, $data);
        if (!$result['success']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => $result['message'])));
            return $res;
        } else {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được chỉnh sửa thành công!'));
            $res->setStatusCode(200);
            $res->setContent(json_encode(array('message' => $result['message'], 'redirect_url' => $this->generateUrl('application_for_school_classList'))));
            return $res;
        }
    }

    public function editStudentAction($id, $profileId)
    {
        $res = new Response();
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            $res->setStatusCode(404);
            return $res;
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            $res->setStatusCode(404);
            return $res;
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array(
                'id' => $id,
                'school' => $school->getId()
            ));
        if (empty($class)) {
            $res->setStatusCode(404);
            return $res;
        }
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->findOneBy(array(
                'id' => $profileId,
                'lop' => $id
            ));
        if (empty($profile)) {
            $res->setStatusCode(404);
            return $res;
        }
        $html = $this->renderView('@App/School/_form-edit-student.html.twig', array('class' => $class, 'profile' => $profile));
        $res->setContent($html);
        $res->setStatusCode(200);
        return $res;
    }

    public function doEditStudentAction($id, $profileId, Request $request)
    {
        $res = new Response();
        $schoolId = $this->get('session')->get('school');
        if (empty($schoolId)) {
            $res->setStatusCode(404);
            return $res;
        }
        $school = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            $res->setStatusCode(404);
            return $res;
        }
        $class = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->findOneBy(array(
                'id' => $id,
                'school' => $school->getId()
            ));
        if (empty($class)) {
            $res->setStatusCode(404);
            return $res;
        }
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->findOneBy(array(
                'id' => $profileId,
                'lop' => $id
            ));
        if (empty($profile)) {
            $res->setStatusCode(404);
            return $res;
        }
        $data = $request->request->all();
        $result = $this->get('application.service.profile')
            ->editStudent($profile, $data);
        if (!$result['success']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => $result['message'])));
            return $res;
        } else {
            $this->addFlash('notice', $this->get('translator')->trans('Dữ liệu được chỉnh sửa thành công!'));
            $res->setStatusCode(200);
            $res->setContent(json_encode(array(
                'message' => $result['message'],
                'redirect_url' => $this->generateUrl('application_for_school_studentInClass', array('id' => $id))
            )));
            return $res;
        }
    }

    public function ajaxLoadAllProfilesAction(Request $request)
    {
        $value = $request->get('value');
        $profiles = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->get(array(
                'keyword' => $value,
                //'exclusive_class' => $request->get('currentClass')
            ));
        $data = array();
        if (!empty($profiles)) {
            foreach ($profiles as $profile) {
                $classes = $profile->getClasses();
                $classIds = array();
                if (!empty($classes)) {
                    foreach ($classes as $class) {
                        $classIds[] = $class->getId();
                    }
                }
                if (!empty($classIds) && in_array($request->get('currentClass'), $classIds)) {
                    continue;
                }
                $data[] = $profile->getProfileId() . ' - ' . $profile->getName();
            }
        }
        $res = new Response();
        $res->setStatusCode(200);
        $res->setContent(json_encode($data));
        return $res;
    }

    public function ajaxLoadProfileAction(Request $request)
    {
        $value = $request->get('value');
        $data = explode('-', $value);
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->findOneBy(array(
                'profile_id' => $data[0]
            ));
        if (empty($profile)) {
            $res = new Response();
            $res->setStatusCode(404);
            return $res;
        }
        $res = new Response();
        $res->setStatusCode(200);
        $res->setContent(json_encode(array(
            'name' => $profile->getName(),
            'dateOfBirth' => date_format($profile->getDateOfBirth(), 'd/m/Y'),
            'email' => $profile->getEmail(),
            'address' => $profile->getAddress(),
            'phone' => $profile->getPhone(),
            'gender' => $profile->getGender(),
        )));
        return $res;
    }

    public function ajaxAddOneStudentToClassAction(Request $request)
    {
        $data = $request->request->all();
        $result = $this->get('application.service.profile')
            ->addOneStudentToClass($data);
        $res = new Response();
        if (!$result['success']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => $result['message'])));
            return $res;
        } else {
            $res->setStatusCode(200);
            $res->setContent(json_encode(array('message' => $result['message'], 'redirect_url' => $this->generateUrl('application_for_school_classList'))));
            return $res;
        }
    }

    public function addBmiProfileAction(Request $request)
    {
        $data = $request->request->all();
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:Profile')
            ->findOneBy(array(
                'profile_id' => $data['profileId']
            ));
        if (empty($profile)) {
            throw $this->createNotFoundException();
        }
        $result = $this->get('amz.service.bmi')->calculate($profile->getId(), $data['weight'], $data['height'], $data['date']);
        //var_dump($result);die();
        $res = new Response();
        //$res->setContent(json_encode($result));
        //return $res;
        if (!$result['result']) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => 'Không thể thêm')));
            return $res;
        } else {
            $res->setStatusCode(200);
            $res->setContent(json_encode(array('message' => 'Thêm thành công')));
            return $res;
        }
    }

    public function editBmiProfileAction(Request $request)
    {
        $data = $request->request->all();
        $profile = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:ProfileBmiResult')
            ->find($data['profileId']);
        if (empty($profile)) {
            throw $this->createNotFoundException();
        }
        $profile->setWeight($data['weight']);
        $profile->setHeight($data['height']);
        $date = date_create_from_format('d/m/Y H:i:s', $data['date'] . ' 00:00:00');

        $profile->setDayWeight($date);
        $profile->setBmi($data['bmi']);
        $profile->setResult($data['result']);
        $profile->setRecommend($data['recommend']);
        $result = $this->get('amz_db.service.query')
            ->getRepository('AMZProfileBundle:ProfileBmiResult')
            ->update($profile);
        $res = new Response();

        if (!$result) {
            $res->setStatusCode(400);
            $res->setContent(json_encode(array('message' => 'Không thể sửa')));
            return $res;
        } else {
            $res->setStatusCode(200);
            $res->setContent(json_encode(array('message' => 'Sửa thành công')));
            return $res;
        }
    }

    public function heightWeightPrintAction(Request $request)
    {
        $pagination = $this->get('session')->get('pagination');
        return $this->render('AppBundle:School:height-weight-print.html.twig', array(
            'pagination' => $pagination));
    }
}
