<?php

namespace AppBundle\Service;

use AMZ\BackendBundle\Service\DBQueryService;
use AMZ\BackendBundle\Service\ValidateService;
use AMZ\ProfileBundle\Entity\Profile;
use AMZ\ProfileBundle\Entity\SchoolClass;
use AMZ\ProfileBundle\Service\BMIService;
use AMZ\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class ProfileService
{
    const NUM_COLUMNS_IMPORT_PROFILE_TO_CLASS = 5;
    const TEXT_COLUMNS_IMPORT_PROFILE_TO_CLASS = 'E';

    const NUM_COLUMNS_IMPORT_HEIGHT_WEIGHT_TO_PROFILE = 5;
    const TEXT_COLUMNS_IMPORT_HEIGHT_WEIGHT_TO_PROFILE = 'E';

    private $queryService;
    private $tokenStorage;
    private $validator;
    private $fileImportDirectory;
    private $session;
    private $bmiService;

    public function __construct(DBQueryService $queryService,
                                TokenStorage $tokenStorage, ValidateService $validator,
                                $fileImportDirectory,
                                Session $session,
                                BMIService $bmiService
    )
    {
        $this->queryService = $queryService;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
        $this->fileImportDirectory = $fileImportDirectory;
        $this->session = $session;
        $this->bmiService = $bmiService;
    }

    public function addClass($parameters)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }

        if (empty($data['code']) || empty($data['name']) ||
            empty($data['schoolUnit']) || empty($data['schoolYear'])
        ) {
            return array(
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            );
        }
        $schoolId = $this->session->get('school');
        if (empty($schoolId)) {
            return array(
                'success' => false,
                'message' => 'Chưa chọn trường học'
            );
        }
        $school = $this->queryService
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return array(
                'success' => false,
                'message' => 'Chưa chọn trường học'
            );
        }
        $count = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->total(array(
                'code' => $data['code'],
                'school' => $school->getId(),
                'school_year' => $data['schoolYear'],
            ));
        if (0 < $count) {
            return array(
                'success' => false,
                'message' => 'Mã lớp đã tồn tại'
            );
        }
        $schoolUnit = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->find($data['schoolUnit']);
        $schoolYear = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($data['schoolYear']);

        $entity = new SchoolClass();
        $entity->setName($data['name']);
        $entity->setCode($data['code']);
        $entity->setSchool($school);
        $entity->setSchoolClassUnit($schoolUnit);
        $entity->setSchoolYear($schoolYear);

        $result = $this->queryService->getRepository('AMZProfileBundle:SchoolClass')
            ->insert($entity);

        if (false !== $result) {
            return array(
                'success' => true,
                'message' => 'Lớp đã được thêm thành công'
            );
        } else {
            return array(
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
            );
        }
    }

    public function editClass($entity, $parameters)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }

        if (empty($data['code']) || empty($data['name']) ||
            empty($data['schoolUnit']) || empty($data['schoolYear'])
        ) {
            return array(
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            );
        }
        $schoolId = $this->session->get('school');
        if (empty($schoolId)) {
            return array(
                'success' => false,
                'message' => 'Chưa chọn trường học'
            );
        }
        $school = $this->queryService
            ->getRepository('AMZProfileBundle:School')
            ->find($schoolId);
        if (empty($school)) {
            return array(
                'success' => false,
                'message' => 'Chưa chọn trường học'
            );
        }
        $count = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->total(array(
                'code' => $data['code'],
                'exclusive_code' => $entity->getCode(),
                'school' => $school->getId(),
                'school_year' => $data['schoolYear'],
            ));
        if (0 < $count) {
            return array(
                'success' => false,
                'message' => 'Mã lớp đã tồn tại'
            );
        }
        $schoolUnit = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClassUnit')
            ->find($data['schoolUnit']);
        $schoolYear = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolYear')
            ->find($data['schoolYear']);

        $entity->setName($data['name']);
        $entity->setCode($data['code']);
        $entity->setSchool($school);
        $entity->setSchoolClassUnit($schoolUnit);
        $entity->setSchoolYear($schoolYear);

        $result = $this->queryService->getRepository('AMZProfileBundle:SchoolClass')
            ->update($entity);

        if (false !== $result) {
            return array(
                'success' => true,
                'message' => 'Lớp đã được chỉnh sửa thành công'
            );
        } else {
            return array(
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
            );
        }
    }

    public function importHeightWeightToProfileStep1($parameters, $file)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }
        if (empty($data['schoolYear'])) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn năm học'
            );
        }
        if (empty($data['schoolClass'])) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn lớp'
            );
        }
        if (empty($file)) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn file Excel'
            );
        }
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, array('xls', 'xlsx'))) {
            return array(
                'success' => false,
                'message' => $extension . ' - Yêu cầu định dạng *.xls hoặc *.xlsx'
            );
        }
        $fileName = md5(uniqid()) . '.' . $extension;
        $file->move(
            $this->fileImportDirectory,
            $fileName
        );
        $fullLink = $this->fileImportDirectory . '/' . $fileName;

        $phpExcel = \PHPExcel_IOFactory::load($fullLink);
        $sheet = $phpExcel->getActiveSheet();
        $numberColumns = $sheet->getHighestColumn(1);
        if (self::TEXT_COLUMNS_IMPORT_HEIGHT_WEIGHT_TO_PROFILE != $numberColumns) {
            return array(
                'success' => false,
                'message' => 'Vui lòng dùng Danh sách chuẩn để thêm - Số cột không hợp lệ'
            );
        }

        $numberRows = $sheet->getHighestRow();
        if (2 > $numberRows) {
            return array(
                'success' => false,
                'message' => 'Chưa đưa dữ liệu vào File import'
            );
        }
        $dataFile = array();
        for ($i = 2; $i <= $numberRows; $i++) {
            $dataRow = array();
            for ($j = 0; $j < self::NUM_COLUMNS_IMPORT_HEIGHT_WEIGHT_TO_PROFILE; $j++) {
                $dataRow[$j] = trim($sheet->getCellByColumnAndRow($j, $i));
            }
            $dataFile[$i]['row'] = $dataRow;
        }
        $dataFile = $this->validateDataImportHeightWeightToProfile($dataFile);
        $sessionData = $dataFile;
        $sessionData['schoolClass'] = $data['schoolClass'];
        $this->session->set('import-profile-to-class', $sessionData);
        unset($fullLink);
        return array(
            'success' => true,
            'data' => $dataFile
        );
    }

    private function validateDataImportHeightWeightToProfile($data)
    {
        foreach ($data as $key => $value) {
            $temp = $value['row'];
            if (empty($temp[0])) {
                $data[$key]['status'] = 0;
                $data[$key]['action'] = '';
                $data[$key]['message'] = 'Không tồn tại HS';
            } else {
                $profile = $this->queryService
                    ->getRepository('AMZProfileBundle:Profile')
                    ->findOneBy(array(
                        'profile_id' => $temp[0]
                    ));
                if (empty($profile)) {
                    $data[$key]['status'] = 0;
                    $data[$key]['action'] = '';
                    $data[$key]['message'] = 'Không tồn tại Mã HS';
                } else {
                    if (empty($temp[2])) {
                        $data[$key]['status'] = 0;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = 'Bắt buộc nhập chiều cao';
                    } elseif (empty($temp[3])) {
                        $data[$key]['status'] = 0;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = 'Bắt buộc nhập cân nặng';
                    } elseif (!$this->validator->isNumber($temp[2])) {
                        $data[$key]['status'] = 0;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = 'Chiều cao là 1 số > 0';
                    } elseif (!$this->validator->isNumber($temp[3])) {
                        $data[$key]['status'] = 0;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = 'Cân nặng là 1 số > 0';
                    } elseif (empty($temp[4])) {
                        $data[$key]['status'] = 0;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = 'Bắt buộc nhập ngày đo';
                    } elseif (!$this->validator->importDateFormat($temp[4])) {
                        $data[$key]['status'] = 0;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = 'Nhập đúng định dạng ngày đo';
                    } else {
                        $data[$key]['status'] = 1;
                        $data[$key]['action'] = '';
                        $data[$key]['message'] = '';
                    }
                }
            }
        }
        return $data;
    }

    public function importProfileToClassStep1($parameters, $file)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }
        if (empty($data['schoolYear'])) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn năm học'
            );
        }
        if (empty($data['schoolClass'])) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn lớp'
            );
        }
        if (empty($file)) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn file Excel'
            );
        }
        $extension = $file->guessExtension();
        if (!in_array($extension, array('xls', 'xlsx'))) {
            return array(
                'success' => false,
                'message' => 'File excel định dạng *.xls hoặc *.xlsx'
            );
        }
        $fileName = md5(uniqid()) . '.' . $extension;
        $file->move(
            $this->fileImportDirectory,
            $fileName
        );
        $fullLink = $this->fileImportDirectory . '/' . $fileName;

        $phpExcel = \PHPExcel_IOFactory::load($fullLink);
        $sheet = $phpExcel->getActiveSheet();
        $numberColumns = $sheet->getHighestColumn(1);
        if (self::TEXT_COLUMNS_IMPORT_PROFILE_TO_CLASS != $numberColumns) {
            return array(
                'success' => false,
                'message' => 'Vui lòng dùng Danh sách chuẩn để thêm - Số cột không hợp lệ'
            );
        }

        $numberRows = $sheet->getHighestRow();
        if (2 > $numberRows) {
            return array(
                'success' => false,
                'message' => 'Chưa đưa dữ liệu vào File import'
            );
        }
        $dataFile = array();
        for ($i = 2; $i <= $numberRows; $i++) {
            $dataRow = array();
            for ($j = 0; $j < self::NUM_COLUMNS_IMPORT_PROFILE_TO_CLASS; $j++) {
                $dataRow[$j] = trim($sheet->getCellByColumnAndRow($j, $i));
            }
            $dataFile[$i]['row'] = $dataRow;
        }
        $dataFile = $this->validateDataImportProfileToClass($dataFile);
        $sessionData = $dataFile;
        $sessionData['schoolClass'] = $data['schoolClass'];
        $this->session->set('import-profile-to-class', $sessionData);
        unset($fullLink);
        return array(
            'success' => true,
            'data' => $dataFile
        );
    }

    private function validateDataImportProfileToClass($data)
    {
        foreach ($data as $key => $value) {
            $temp = $value['row'];
            if (empty($temp[0]) && empty($temp[1])) {
                $data[$key]['status'] = 0;
                $data[$key]['action'] = '';
                $data[$key]['message'] = 'Không tồn tại HS';
            } elseif (!empty($temp[0])) {
                $profile = $this->queryService
                    ->getRepository('AMZProfileBundle:Profile')
                    ->findOneBy(array(
                        'profile_id' => $temp[0]
                    ));
                if (empty($profile)) {
                    $data[$key]['status'] = 0;
                    $data[$key]['action'] = '';
                    $data[$key]['message'] = 'Không tồn tại Mã HS';
                } else {
                    $data[$key]['status'] = 1;
                    $data[$key]['action'] = 'old';
                    $data[$key]['message'] = '';
                }
            } else {
                if (!$this->validator->importDateFormat($temp[2])) {
                    $data[$key]['status'] = 0;
                    $data[$key]['action'] = '';
                    $data[$key]['message'] = 'Ngày sinh không hợp lệ';
                } elseif (!in_array($temp[3], array('Nam', 'Nữ'))) {
                    $data[$key]['status'] = 0;
                    $data[$key]['action'] = '';
                    $data[$key]['message'] = 'Giới tính không hợp lệ';
                } else {
                    $data[$key]['status'] = 1;
                    $data[$key]['action'] = 'new';
                    $data[$key]['message'] = '';
                }
            }
        }
        return $data;
    }

    public function insertDataImportProfileToClass()
    {
        $data = $this->session->get('import-profile-to-class');
        $this->session->set('import-profile-to-class', null);
        $schoolClass = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->find($data['schoolClass']);
        unset($data['schoolClass']);
        $nbrInserted = 0;
        foreach ($data as $key => $val) {
            if (0 == $val['status']) {
                continue;
            }
            $nbrInserted += 1;
            $value = $val['row'];
            if ('new' == $val['action']) {
                $birthDay = date_create_from_format('d/m/Y H:i:s', $value[2] . ' 00:00:00');
                $profile = new Profile();
                $profile->setName($value[1]);
                $profile->setDateOfBirth($birthDay);
                $profile->setGender('Nam' == $value[3] ? Profile::GENDER_MALE : Profile::GENDER_FEMALE);
                $profile->setAddress($value[4]);
                $profile->setEmail($value[5]);
                $profile->setPhone($value[6]);
                $this->queryService
                    ->getRepository('AMZProfileBundle:Profile')
                    ->insert($profile);
                $schoolClass->addProfile($profile);
                $this->queryService
                    ->getRepository('AMZProfileBundle:SchoolClass')
                    ->update($schoolClass);
            } else {
                $profile = $this->queryService
                    ->getRepository('AMZProfileBundle:Profile')
                    ->findOneBy(array('profile_id' => $value[0]));
                $schoolClass->addProfile($profile);
                $this->queryService
                    ->getRepository('AMZProfileBundle:SchoolClass')
                    ->update($schoolClass);
            }
        }
        return array(
            'schoolClass' => $schoolClass,
            'nbrInserted' => $nbrInserted
        );
    }

    public function insertDataImportHeightWeightToProfile()
    {
        $data = $this->session->get('import-profile-to-class');
        $this->session->set('import-profile-to-class', null);
        $schoolClass = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->find($data['schoolClass']);
        unset($data['schoolClass']);
        $nbrInserted = 0;
        foreach ($data as $key => $val) {
            if (0 == $val['status']) {
                continue;
            }
            $nbrInserted += 1;
            $value = $val['row'];
            $profile = $this->queryService
                ->getRepository('AMZProfileBundle:Profile')
                ->findOneBy(array('profile_id' => $value[0]));

            $this->bmiService->calculate($profile->getId(), $value[3], $value[2], $value[4], $schoolClass);
        }
        return array(
            'schoolClass' => $schoolClass,
            'nbrInserted' => $nbrInserted
        );
    }

    public function editStudent($entity, $parameters)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }

        if (empty($data['name']) || empty($data['dateOfBirth']) || empty($data['gender'])) {
            return array(
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            );
        }
        $res = $this->validator->importDateFormat($data['dateOfBirth']);
        if (!$res) {
            return array(
                'success' => false,
                'message' => 'Ngày sinh không hợp lệ'
            );
        }
        if (!empty($data['email'])) {
            $res = $this->validator->email($data['email']);
            if (!$res) {
                return array(
                    'success' => false,
                    'message' => 'Email không hợp lệ'
                );
            }
        }
        $entity->setName($data['name']);
        $dateOfBirth = date_create_from_format('d/m/Y H:i:s', $data['dateOfBirth'] . ' 00:00:00');
        $entity->setDateOfBirth($dateOfBirth);
        $entity->setGender($data['gender']);
        $entity->setPhone($data['phone']);
        $entity->setAddress($data['address']);
        $entity->setEmail($data['email']);

        $result = $this->queryService->getRepository('AMZProfileBundle:Profile')
            ->update($entity);

        if (false !== $result) {
            return array(
                'success' => true,
                'message' => 'Học sinh đã được chỉnh sửa thành công'
            );
        } else {
            return array(
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
            );
        }
    }

    public function addOneStudentToClass($parameters)
    {
        $data = array();
        foreach ($parameters as $key => $value) {
            $data[$key] = trim($value);
        }

        $schoolClass = $this->queryService
            ->getRepository('AMZProfileBundle:SchoolClass')
            ->find($data['class']);
        if (empty($schoolClass)) {
            return array(
                'success' => false,
                'message' => 'Sai lớp'
            );
        }

        if (empty($data['code']) && empty($data['name'])) {
            return array(
                'success' => false,
                'message' => 'Vui lòng chọn HS hoặc nhập mới'
            );
        }
        if (empty($data['code'])) {
            if (empty($data['dateOfBirth'])) {
                return array(
                    'success' => false,
                    'message' => 'Vui lòng nhập ngày sinh'
                );
            }
            $res = $this->validator->importDateFormat($data['dateOfBirth']);
            if (!$res) {
                return array(
                    'success' => false,
                    'message' => 'Ngày sinh không hợp lệ'
                );
            }
            if (!empty($data['email'])) {
                $res = $this->validator->email($data['email']);
                if (!$res) {
                    return array(
                        'success' => false,
                        'message' => 'Email không hợp lệ'
                    );
                }
            }

            $birthDay = date_create_from_format('d/m/Y H:i:s', $data['dateOfBirth'] . ' 00:00:00');
            $profile = new Profile();
            $profile->setName($data['name']);
            $profile->setDateOfBirth($birthDay);
            $profile->setGender($data['gender']);
            $profile->setAddress($data['address']);
            $profile->setEmail($data['email']);
            $profile->setPhone($data['phone']);
            $this->queryService
                ->getRepository('AMZProfileBundle:Profile')
                ->insert($profile);
            $schoolClass->addProfile($profile);
            $result = $this->queryService
                ->getRepository('AMZProfileBundle:SchoolClass')
                ->update($schoolClass);

            if (false !== $result) {
                return array(
                    'success' => true,
                    'message' => 'HS đã được thêm vào lớp thành công'
                );
            } else {
                return array(
                    'success' => false,
                    'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
                );
            }
        } else {
            $temp = explode('-', $data['code']);
            $profile = $this->queryService
                ->getRepository('AMZProfileBundle:Profile')
                ->findOneBy(array(
                    'profile_id' => $temp[0]
                ));
            if (empty($profile)) {
                return array(
                    'success' => false,
                    'message' => 'Học sinh không tồn tại'
                );
            }
            $schoolClass->addProfile($profile);
            $result = $this->queryService
                ->getRepository('AMZProfileBundle:SchoolClass')
                ->update($schoolClass);

            if (false !== $result) {
                return array(
                    'success' => true,
                    'message' => 'HS đã được thêm vào lớp thành công'
                );
            } else {
                return array(
                    'success' => false,
                    'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
                );
            }
        }
    }
}