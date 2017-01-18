<?php

namespace AMZ\ProfileBundle\Service;

use AMZ\ProfileBundle\Entity\Profile;
use Doctrine\ORM\EntityManager;
use AMZ\ProfileBundle\Entity\ProfileBmiResult;
use AMZ\UserBundle\Entity\UserProfileBmiResult;
use AMZ\BackendBundle\Service\DBQueryService;

const _0_5_YO_CATEGORY = 1;
const _5_19_YO_CATEGORY = 2;
const _over_19_YO_CATEGORY = 3;

const _0_6_M_SUBCATEGORY = 1;
const _6_12_M_SUBCATEGORY = 2;
const _12_24_M_SUBCATEGORY = 3;
const _24_60_M_SUBCATEGORY = 4;
class BMIService
{
    private $em;
    private $repository;
    private  $queryService;

    public function __construct(EntityManager $entityManager, DBQueryService $queryService)
    {
        $this->em = $entityManager;
        $this->queryService = $queryService;
    }

    public function getRepository($entityName)
    {
        $this->repository = $this->em->getRepository($entityName);
        return $this;
    }

    public function calculate($profileID, $weight, $length, $dayWeight = null, $schoolClass = null)
    {
        try {
            $oProfile = $this->queryService->getRepository('AMZProfileBundle:Profile')
                ->find($profileID);
            if (!empty($oProfile)){
                $length = $this->roundHeight($length);
                $bmiValue = 0;
                $resultType = 0;
                $gender = $oProfile->getGender();

                //get category
                $aCategory = $this->getCategory($oProfile->getDateOfBirth());
                $category = $aCategory[0];
                $subCategory = $aCategory[1];
                $month = $aCategory[2];

                $result = array();
                $result['fullName'] = $oProfile->getName();
                $result['weight'] = $weight;
                $result['length'] = $length;
                $result['month'] = $month;
                $result['category'] = $category;
                $result['birthday'] = $oProfile->getDateOfBirth()->format('d/m/Y');
                $result['gender'] = $gender;
                $result['result'] = '';
                $result['resultValue'] = '';
                $result['recommend'] = '';
                $result['advise'] = '';
                $result['bmi'] = '';
                $result['bmi_from'] = '';
                $result['bmi_to'] = '';
                $result['heightMedian'] = '';
                $result['height_from'] = '';
                $result['height_to'] = '';

                if ($category == _0_5_YO_CATEGORY) {
                    $criteria = array(
                        'category' => $category,
                        'length' => $length,
                        'month' =>$month,
                        'gender' => $gender
                    );
                    //echo "<pre>";var_dump($criteria);die();
                    // CC/T
                    $lengthStandard = $this->queryService->getRepository('AMZProfileBundle:LengthForAge')
                        ->findOneBy($criteria);
                    if (empty($lengthStandard)) {
                        $otherCriteria = array(
                            'category' => $category,
                            'length' => $length,
                            'gender' => $gender
                        );
                        $lengthStandard = $this->queryService->getRepository('AMZProfileBundle:LengthForAge')
                            ->findOneBy($otherCriteria);
                    }
                    // CN/CC
                    if ($subCategory <= 3) $criteria['category'] = 0; //0-2 year old
                    // CN/CC
                    $weightStandard = $this->queryService->getRepository('AMZProfileBundle:WeightForLength')
                        ->findOneBy($criteria);
                    $referWeight = $this->queryService->getRepository('AMZProfileBundle:WeightForAge')
                        ->findOneBy($criteria);
                    if (!empty($referWeight)) {
                        $result['bmi'] = $referWeight->getMedian();
                        $result['bmi_from'] = $referWeight->getN1sd();
                        $result['bmi_to'] = $referWeight->getP2sd();
                    } else {
                        $result['bmi'] = '';
                        $result['bmi_from'] = '';
                        $result['bmi_to'] = '';
                    }
                    if (!empty($weightStandard) && !empty($lengthStandard)) {
                        $_3sd = $weightStandard->getN3sd();
                        $_2sd = $weightStandard->getN2sd();
                        $_1sd = $weightStandard->getN1sd();
                        $median = $weightStandard->getMedian();
                        $P3sd = $weightStandard->getP3sd();
                        $P2sd = $weightStandard->getP2sd();
                        $P1sd = $weightStandard->getP1sd();

                        $length_3sd = $lengthStandard->getN3sd();
                        $length_2sd = $lengthStandard->getN2sd();
                        $length_1sd = $lengthStandard->getN1sd();
                        $length_median = $lengthStandard->getMedian();
                        $length_P3sd = $lengthStandard->getP3sd();
                        $length_P2sd = $lengthStandard->getP2sd();
                        $length_P1sd = $lengthStandard->getP1sd();

                        $result['heightMedian'] = $length_median;
                        $result['height_from'] = $length_1sd;
                        $result['height_to'] = $length_P2sd;
                        //  -2SD ≤ CN/CC ≤ -1SD và  CC/T < - 3SD
                        switch (true) {
                            case $weight < $_3sd: //CN/CC < -3SD
                                if ($length < $length_3sd) { //CC/T < -3SD
                                    $resultType = "5.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //-3SD ≤CC/T < -2SD
                                    $resultType = "5.3";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //-2SD ≤ CC/T ≤  -1SD
                                    $resultType = "6.1";
                                } else if ($length > $length_1sd) { //- 1SD < CC/T
                                    $resultType = "7.1";
                                }
                                break;
                            case $weight >= $_3sd && $weight < $_2sd: //-3SD ≤ CN/CC < -2SD
                                if ($length < $length_3sd) { //CC/T < -3SD
                                    $resultType = "5.2";
                                } else if ($length >= $length_3sd && $length < $length_2sd){ //-3SD ≤CC/T < -2SD
                                    $resultType = "5.4";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //-2SD ≤ CC/T  ≤  -1SD
                                    $resultType = "6.2";
                                } else if ($length > $length_1sd) { //- 1SD < CC/T
                                    $resultType = "7.2";
                                }
                                break;
                            case $weight >= $_2sd && $weight <= $_1sd: //-2SD ≤ CN/CC  ≤ -1SD
                                //-2SD ≤ CC/T ≤  -1SD
                                if ($length >= $length_2sd && $length <= $length_1sd) {
                                    $resultType = "2";
                                } else if ($length > $length_1sd) { //- 1SD  < CC/T
                                    $resultType = "4";
                                } else if ($length < $length_3sd) { //CC/T < - 3SD
                                    $resultType = "8.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //-3SD ≤CC/T < -2SD
                                    $resultType = "8.2";
                                }
                                break;
                            case $weight > $_1sd && $weight <= $P2sd: // -1SD < CN/CC ≤ + 2SD
                                if ($length > $length_1sd) {
                                    $resultType = "1";
                                } else if ($length_2sd <= $length && $length <= $length_1sd) { //-2SD ≤ CC/T ≤  -1SD
                                    $resultType = "3";
                                } else if ($length < $length_3sd) { //-1SD < CN/CC ≤ + 2SD và CC/T < - 3SD
                                    $resultType = "9.1";
                                } else if ($length >= $length_3sd &&  $length < $length_2sd) { //-1SD < CN/CC ≤ + 2SD và -3SD ≤CC/T < -2SD
                                    $resultType = "9.2";
                                }
                                break;
                            case $weight > $P2sd && $weight <= $P3sd: //+2SD < CN/CC≤ +3SD
                                if ($length < $length_3sd) { //+2SD < CN/CC≤ +3SD  và  CC/T < -3SD
                                    $resultType = "10.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { // +2SD < CN/CC≤ +3SD   và      -3SD ≤CC/T < -2SD
                                    $resultType = "10.3";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //+2SD < CN/CC ≤ +3SD   và   -2SD ≤ CC/T ≤  -1SD
                                    $resultType = "11.1";
                                } else if ($length > $length_1sd) { //+2SD < CN/CC≤ +3SD      và            -1 SD < CC/T
                                    $resultType = "12.1";
                                }
                                break;
                            case $weight > $P3sd:
                                if ($length < $length_3sd) { //CN/CC > +3SD    và     CC/T < -3SD
                                    $resultType = "10.2";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //CN/CC > +3SD và -3SD ≤CC/T < -2SD
                                    $resultType = "10.4";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //CN/CC > +3SD  và  -2SD ≤ CC/T ≤  -1SD
                                    $resultType = "11.2";
                                } else if ($length > $length_1sd) { //CN/CC > +3SD  và   -1 SD < CC/T
                                    $resultType = "12.2";
                                }
                                break;
                            default:
                                break;
                        }
                        //echo $resultType;die();
                    }
                } else if ($category == _5_19_YO_CATEGORY) {
                    $criteria = array(
                        'month' => $month,
                        'gender' => $gender
                    );
                    $bmiStandard = $this->queryService->getRepository('AMZProfileBundle:BmiForAge')
                        ->findOneBy($criteria);
                    $heightStandard = $this->queryService->getRepository('AMZProfileBundle:HeightForAge')
                        ->findOneBy($criteria);
                    if (!empty($bmiStandard) && !empty($heightStandard)) {
                        $bmiMedian = $bmiStandard->getMedian()*($length/100)*($length/100);
                        $result['bmi'] = $bmiMedian;
                        $bmiValue = $bmiMedian;
                        $bmi_3sd = $bmiStandard->getN3sd()*($length/100)*($length/100);
                        $bmi_2sd = $bmiStandard->getN2sd()*($length/100)*($length/100);
                        $bmi_1sd = $bmiStandard->getN1sd()*($length/100)*($length/100);
                        $bmi1sd = $bmiStandard->getP1sd()*($length/100)*($length/100);
                        $bmi2sd = $bmiStandard->getP2sd()*($length/100)*($length/100);
                        $bmi3sd = $bmiStandard->getP3sd()*($length/100)*($length/100);
                        $result['bmi_from'] = $bmi_2sd;
                        $result['bmi_to'] = $bmi1sd;
                        $heightMedian = $heightStandard->getMedian();
                        $height_3sd = $heightStandard->getN3sd();
                        $height_2sd = $heightStandard->getN2sd();
                        $height_1sd = $heightStandard->getN1sd();
                        $height1sd = $heightStandard->getP1sd();
                        $height2sd = $heightStandard->getP2sd();
                        $height3sd = $heightStandard->getP3sd();
                        $result['heightMedian'] = $heightMedian;
                        $result['height_from'] = $height_2sd;
                        $result['height_to'] = $height2sd;
                        switch (true) {
                            case $weight >= $bmi_1sd && $weight <= $bmi1sd: //-1SD ≤ BMI theo tuổi và giới ≤ +1SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 1;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 2;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 8;
                                }
                                break;
                            case $weight >= $bmi_2sd && $weight < $bmi_1sd: //-2SD ≤ BMI theo tuổi và giới < -1SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 3;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 4;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 9;
                                }
                                break;
                            case $weight < $bmi_2sd: //BMI theo tuổi và giới < -2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 5;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 6;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 7;
                                }
                                break;
                            case $weight > $bmi1sd && $weight <= $bmi2sd: //+1SD < BMI theo tuổi và giới ≤ +2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 10;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 11;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 12;
                                }
                                break;
                            case $weight > $bmi2sd: //BMI theo tuổi và giới > +2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 13;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 14;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 15;
                                }
                                break;
                            default:
                                $resultType = 0;
                                break;
                        }
                    }
                } else if ($category == _over_19_YO_CATEGORY) {
                    $bmi = $weight/(($length/100)*($length/100));
                    $bmiValue = $bmi;
                    if ($bmi < 18.5) {
                        $resultType = 1;
                    } else if ($bmi >= 18.5 && $bmi <= 24.9) {
                        $resultType = 2;
                    } else if ($bmi == 25) {
                        $resultType = 3;
                    } else if ($bmi > 25 && $bmi <= 29.9) {
                        $resultType = 4;
                    } else if ($bmi >=30 && $bmi <=34.9) {
                        $resultType = 5;
                    } else if ($bmi >= 35 && $bmi <= 39.9) {
                        $resultType = 6;
                    } else if ($bmi >= 40) {
                        $resultType = 7;
                    }

                }
                $bmiResult = $this->getBmiResult($category, $subCategory, $resultType);
                //echo 'Category: '.$category.'<br>Sub category: '.$subCategory.'Result type: '.$resultType.'Gender: '.$gender;
                //echo "<pre>";echo count($bmiResult);die();
                if (!empty($bmiResult)) {
                    $result['result'] = $bmiResult->getResult();
                    $result['resultValue'] = $bmiResult->getResultValue();
                    $result['advise'] = $bmiResult->getAdvise();
                    $result['recommend'] = $bmiResult->getRecommend();

                    $oProfileBmiResult = new ProfileBmiResult();
                    $oProfileBmiResult->setAdvise($result['advise']);
                    $oProfileBmiResult->setGender($gender);
                    $oProfileBmiResult->setRecommend($result['recommend']);
                    $oProfileBmiResult->setProfile($oProfile);
                    $oProfileBmiResult->setResult($result['result']);
                    $oProfileBmiResult->setResultValue($result['resultValue']);
                    $oProfileBmiResult->setResultType($resultType);
                    $oProfileBmiResult->setSubCategory($subCategory);
                    $oProfileBmiResult->setCategory($category);
                    $oProfileBmiResult->setStandardHeight('');
                    $oProfileBmiResult->setStandardWeight('');
                    if (!empty($dayWeight)) {
                        $date = date_create_from_format('d/m/Y H:i:s', $dayWeight . ' 00:00:00');
                        $oProfileBmiResult->setDayWeight($date);
                        $oProfile->setLastDayWeight($date);
                    }
                    if (!empty($schoolClass)) {
                        $oProfileBmiResult->setSchoolClass($schoolClass);
                    }
                    $oProfileBmiResult->setHeight($length);
                    $oProfileBmiResult->setWeight($weight);


                    $this->queryService->getRepository('AMZProfileBundle:ProfileBmiResult')
                        ->insert($oProfileBmiResult);
                    $oProfile->setLastHeight($length);
                    $oProfile->setLastWeight($weight);
                    $oProfile->setLastResult($result['result']);



//                    Hoàng Lê: add calculate bmi
                    $bmiValue = $weight/(($length/100)*($length/100));
                    $oProfile->setLastBMI($bmiValue);

                    $this->queryService->getRepository('AMZProfileBundle:Profile')
                        ->update($oProfile);

                }
                //echo "<pre>";//die();
                //var_dump($result);die();
                return $result;
            }
        } catch(\Exception $e){
            var_dump($e->getMessage(), 'calculate');die();
            return NULL;
        }

    }

    public function userProfileCalculate($aUserProfile)
    {
        try {
            if (!empty($aUserProfile)){

                $resultType = 0;
                $gender = $aUserProfile['gender'];
                $bmiValue = 0;
                $birthday = \DateTime::createFromFormat('d/m/Y',$aUserProfile['birthday']);
                $aCategory = $this->getCategory($birthday->format('Y-m-d'));
                $category = $aCategory[0];
                $subCategory = $aCategory[1];
                $month = $aCategory[2];
                $weight = $aUserProfile['weight'];
                $length = $aUserProfile['length'];
                $length = $this->roundHeight($length);
                $result = array();
                $result['fullName'] = $aUserProfile['firstName'].' '.$aUserProfile['lastName'];
                $result['firstName'] = $aUserProfile['firstName'];
                $result['lastName'] = $aUserProfile['lastName'];
                $result['weight'] = $weight;
                $result['length'] = $length;
                $result['month'] = $month;
                $result['birthday'] = $aUserProfile['birthday'];
                $result['gender'] = $gender;
                $result['result'] = '';
                $result['resultValue'] = '';
                $result['recommend'] = '';
                $result['advise'] = '';
                $result['bmi'] = '';
                $result['bmi_from'] = '';
                $result['bmi_to'] = '';
                $result['heightMedian'] = '';
                $result['height_from'] = '';
                $result['height_to'] = '';

                if ($category == _0_5_YO_CATEGORY) {
                    $criteria = array(
                        'category' => $category,
                        'length' => $length,
                        'month' =>$month,
                        'gender' => $gender
                    );
                    //echo "<pre>";var_dump($criteria);die();
                    // CC/T
                    $lengthStandard = $this->queryService->getRepository('AMZProfileBundle:LengthForAge')
                        ->findOneBy($criteria);
                    if (empty($lengthStandard)) {
                        $otherCriteria = array(
                            'category' => $category,
                            'length' => $length,
                            'gender' => $gender
                        );
                        $lengthStandard = $this->queryService->getRepository('AMZProfileBundle:LengthForAge')
                            ->findOneBy($otherCriteria);
                    }
                    // CN/CC
                    if ($subCategory <= 3) $criteria['category'] = 0; //0-2 year old
                    // CN/CC
                    $weightStandard = $this->queryService->getRepository('AMZProfileBundle:WeightForLength')
                        ->findOneBy($criteria);

                    $referWeight = $this->queryService->getRepository('AMZProfileBundle:WeightForAge')
                        ->findOneBy($criteria);
                    //var_dump($criteria);die();
                    if (!empty($referWeight)) {
                        $result['bmi'] = $referWeight->getMedian();
                        $result['bmi_from'] = $referWeight->getN1sd();
                        $result['bmi_to'] = $referWeight->getP2sd();
                    } else {
                        $result['bmi'] = '';
                        $result['bmi_from'] = '';
                        $result['bmi_to'] = '';
                    }
                    if (!empty($weightStandard) && !empty($lengthStandard)) {
                        $_3sd = $weightStandard->getN3sd();
                        $_2sd = $weightStandard->getN2sd();
                        $_1sd = $weightStandard->getN1sd();
                        $median = $weightStandard->getMedian();
                        $P3sd = $weightStandard->getP3sd();
                        $P2sd = $weightStandard->getP2sd();
                        $P1sd = $weightStandard->getP1sd();

                        $length_3sd = $lengthStandard->getN3sd();
                        $length_2sd = $lengthStandard->getN2sd();
                        $length_1sd = $lengthStandard->getN1sd();
                        $length_median = $lengthStandard->getMedian();
                        $length_P3sd = $lengthStandard->getP3sd();
                        $length_P2sd = $lengthStandard->getP2sd();
                        $length_P1sd = $lengthStandard->getP1sd();
                        $result['heightMedian'] = $length_median;
                        $result['height_from'] = $length_1sd;
                        $result['height_to'] = $length_P2sd;
                        //  -2SD ≤ CN/CC ≤ -1SD và  CC/T < - 3SD
                        switch (true) {
                            case $weight < $_3sd: //CN/CC < -3SD
                                if ($length < $length_3sd) { //CC/T < -3SD
                                    $resultType = "5.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //-3SD ≤CC/T < -2SD
                                    $resultType = "5.3";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //-2SD ≤ CC/T ≤  -1SD
                                    $resultType = "6.1";
                                } else if ($length > $length_1sd) { //- 1SD < CC/T
                                    $resultType = "7.1";
                                }
                                break;
                            case $weight >= $_3sd && $weight < $_2sd: //-3SD ≤ CN/CC < -2SD
                                if ($length < $length_3sd) { //CC/T < -3SD
                                    $resultType = "5.2";
                                } else if ($length >= $length_3sd && $length < $length_2sd){ //-3SD ≤CC/T < -2SD
                                    $resultType = "5.4";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //-2SD ≤ CC/T  ≤  -1SD
                                    $resultType = "6.2";
                                } else if ($length > $length_1sd) { //- 1SD < CC/T
                                    $resultType = "7.2";
                                }
                                break;
                            case $weight >= $_2sd && $weight <= $_1sd: //-2SD ≤ CN/CC  ≤ -1SD
                                //-2SD ≤ CC/T ≤  -1SD
                                if ($length >= $length_2sd && $length <= $length_1sd) {
                                    $resultType = "2";
                                } else if ($length > $length_1sd) { //- 1SD  < CC/T
                                    $resultType = "4";
                                } else if ($length < $length_3sd) { //CC/T < - 3SD
                                    $resultType = "8.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //-3SD ≤CC/T < -2SD
                                    $resultType = "8.2";
                                }
                                break;
                            case $weight > $_1sd && $weight <= $P2sd: // -1SD < CN/CC ≤ + 2SD
                                if ($length > $length_1sd) {
                                    $resultType = "1";
                                } else if ($length_2sd <= $length && $length <= $length_1sd) { //-2SD ≤ CC/T ≤  -1SD
                                    $resultType = "3";
                                } else if ($length < $length_3sd) { //-1SD < CN/CC ≤ + 2SD và CC/T < - 3SD
                                    $resultType = "9.1";
                                } else if ($length >= $length_3sd &&  $length < $length_2sd) { //-1SD < CN/CC ≤ + 2SD và -3SD ≤CC/T < -2SD
                                    $resultType = "9.2";
                                }
                                break;
                            case $weight > $P2sd && $weight <= $P3sd: //+2SD < CN/CC≤ +3SD
                                if ($length < $length_3sd) { //+2SD < CN/CC≤ +3SD  và  CC/T < -3SD
                                    $resultType = "10.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { // +2SD < CN/CC≤ +3SD   và      -3SD ≤CC/T < -2SD
                                    $resultType = "10.3";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //+2SD < CN/CC ≤ +3SD   và   -2SD ≤ CC/T ≤  -1SD
                                    $resultType = "11.1";
                                } else if ($length > $length_1sd) { //+2SD < CN/CC≤ +3SD      và            -1 SD < CC/T
                                    $resultType = "12.1";
                                }
                                break;
                            case $weight > $P3sd:
                                if ($length < $length_3sd) { //CN/CC > +3SD    và     CC/T < -3SD
                                    $resultType = "10.2";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //CN/CC > +3SD và -3SD ≤CC/T < -2SD
                                    $resultType = "10.4";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //CN/CC > +3SD  và  -2SD ≤ CC/T ≤  -1SD
                                    $resultType = "11.2";
                                } else if ($length > $length_1sd) { //CN/CC > +3SD  và   -1 SD < CC/T
                                    $resultType = "12.2";
                                }
                                break;
                            default:
                                break;
                        }
                        //echo $resultType;die();
                    }
                } else if ($category == _5_19_YO_CATEGORY) {
                    $criteria = array(
                        'month' => $month,
                        'gender' => $gender
                    );
                    $bmiStandard = $this->queryService->getRepository('AMZProfileBundle:BmiForAge')
                        ->findOneBy($criteria);
                    $heightStandard = $this->queryService->getRepository('AMZProfileBundle:HeightForAge')
                        ->findOneBy($criteria);
                    if (!empty($bmiStandard) && !empty($heightStandard)) {
                        $bmiMedian = $bmiStandard->getMedian()*($length/100)*($length/100);
                        $bmiValue = $bmiMedian;
                        $result['bmi'] = round($bmiMedian,1);
                        $bmi_3sd = $bmiStandard->getN3sd()*($length/100)*($length/100);
                        $bmi_2sd = $bmiStandard->getN2sd()*($length/100)*($length/100);
                        $bmi_1sd = $bmiStandard->getN1sd()*($length/100)*($length/100);
                        $bmi1sd = $bmiStandard->getP1sd()*($length/100)*($length/100);
                        $bmi2sd = $bmiStandard->getP2sd()*($length/100)*($length/100);
                        $bmi3sd = $bmiStandard->getP3sd()*($length/100)*($length/100);
                        $result['bmi_from'] = round($bmi_2sd,1);
                        $result['bmi_to'] = round($bmi1sd,1);
                        $heightMedian = $heightStandard->getMedian();
                        $height_3sd = $heightStandard->getN3sd();
                        $height_2sd = $heightStandard->getN2sd();
                        $height_1sd = $heightStandard->getN1sd();
                        $height1sd = $heightStandard->getP1sd();
                        $height2sd = $heightStandard->getP2sd();
                        $height3sd = $heightStandard->getP3sd();
                        $result['heightMedian'] = $heightMedian;
                        $result['height_from'] = $height_2sd;
                        $result['height_to'] = $height2sd;
                        switch (true) {
                            case $weight >= $bmi_1sd && $weight <= $bmi1sd: //-1SD ≤ BMI theo tuổi và giới ≤ +1SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 1;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 2;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 8;
                                }
                                break;
                            case $weight >= $bmi_2sd && $weight < $bmi_1sd: //-2SD ≤ BMI theo tuổi và giới < -1SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 3;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 4;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 9;
                                }
                                break;
                            case $weight < $bmi_2sd: //BMI theo tuổi và giới < -2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 5;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 6;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 7;
                                }
                                break;
                            case $weight > $bmi1sd && $weight <= $bmi2sd: //+1SD < BMI theo tuổi và giới ≤ +2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 10;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 11;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 12;
                                }
                                break;
                            case $weight > $bmi2sd: //BMI theo tuổi và giới > +2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 13;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 14;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 15;
                                }
                                break;
                            default:
                                $resultType = 0;
                                break;
                        }
                    }
                } else if ($category == _over_19_YO_CATEGORY) {
                    $bmi = $weight/(($length/100)*($length/100));
                    $bmiValue = $bmi;
                    if ($bmi < 18.5) {
                        $resultType = 1;
                    } else if ($bmi >= 18.5 && $bmi <= 24.9) {
                        $resultType = 2;
                    } else if ($bmi == 25) {
                        $resultType = 3;
                    } else if ($bmi > 25 && $bmi <= 29.9) {
                        $resultType = 4;
                    } else if ($bmi >=30 && $bmi <=34.9) {
                        $resultType = 5;
                    } else if ($bmi >= 35 && $bmi <= 39.9) {
                        $resultType = 6;
                    } else if ($bmi >= 40) {
                        $resultType = 7;
                    }

                }
                $bmiResult = $this->getBmiResult($category, $subCategory, $resultType);
                //echo 'Category: '.$category.'<br>Sub category: '.$subCategory.'Result type: '.$resultType.'Gender: '.$gender;
                //echo "<pre>";echo count($bmiResult);die();
                if (!empty($bmiResult)) {
                    $result['result'] = $bmiResult->getResult();
                    $result['resultValue'] = $bmiResult->getResultValue();
                    $result['advise'] = $bmiResult->getAdvise();
                    $result['recommend'] = $bmiResult->getRecommend();
                    $oProfileBmiResult = new UserProfileBmiResult();
                    $oProfileBmiResult->setAdvise($result['advise']);
                    $oProfileBmiResult->setGender($gender);
                    $oProfileBmiResult->setRecommend($result['recommend']);
                    //$oProfileBmiResult->setProfile($oProfile);
                    $oProfileBmiResult->setResult($result['result']);
                    $oProfileBmiResult->setResultValue($result['resultValue']);
                    $oProfileBmiResult->setResultType($resultType);
                    $oProfileBmiResult->setSubCategory($subCategory);
                    $oProfileBmiResult->setCategory($category);
                    $oProfileBmiResult->setWeight($result['weight']);
                    $oProfileBmiResult->setLength($result['length']);

                    $this->queryService->getRepository('AMZUserBundle:UserProfileBmiResult')
                        ->insert($oProfileBmiResult);
                    $result['id'] = $oProfileBmiResult->getId();

                }
                //echo "<pre>";//die();
                //var_dump($result);die();
                return $result;
            }
        } catch(\Exception $e){
            //var_dump($e->getMessage());die();
            return NULL;
        }

    }

    public function _calculate($userProfileID, $weight, $length, $dayWeight = null, $schoolClass = null)
    {
        try {
            $oProfile = $this->queryService->getRepository('AMZUserBundle:UserProfile')
                ->find($userProfileID);
            if (!empty($oProfile)){
                $length = $this->roundHeight($length);
                $bmiValue = 0;
                $resultType = 0;
                $gender = $oProfile->getGender();
                //get category
                $aCategory = $this->getCategory($oProfile->getDateOfBirth());
                $category = $aCategory[0];
                $subCategory = $aCategory[1];
                $month = $aCategory[2];

                $result = array();
                $result['fullName'] = $oProfile->getFirstName().' '.$oProfile->getLastName();
                $result['weight'] = $weight;
                $result['length'] = $length;
                $result['month'] = $month;
                $result['category'] = $category;
                $result['birthday'] = $oProfile->getDateOfBirth()->format('d/m/Y');
                $result['gender'] = $gender;
                $result['result'] = '';
                $result['resultValue'] = '';
                $result['recommend'] = '';
                $result['advise'] = '';
                $result['bmi'] = '';
                $result['bmi_from'] = '';
                $result['bmi_to'] = '';
                $result['heightMedian'] = '';
                $result['height_from'] = '';
                $result['height_to'] = '';

                if ($category == _0_5_YO_CATEGORY) {
                    $criteria = array(
                        'category' => $category,
                        'length' => $length,
                        'month' =>$month,
                        'gender' => $gender
                    );
                    //echo "<pre>";var_dump($criteria);die();
                    // CC/T
                    $lengthStandard = $this->queryService->getRepository('AMZProfileBundle:LengthForAge')
                        ->findOneBy($criteria);
                    if (empty($lengthStandard)) {
                        $otherCriteria = array(
                            'category' => $category,
                            'length' => $length,
                            'gender' => $gender
                        );
                        $lengthStandard = $this->queryService->getRepository('AMZProfileBundle:LengthForAge')
                            ->findOneBy($otherCriteria);
                    }
                    // CN/CC
                    if ($subCategory <= 3) $criteria['category'] = 0; //0-2 year old
                    // CN/CC
                    $weightStandard = $this->queryService->getRepository('AMZProfileBundle:WeightForLength')
                        ->findOneBy($criteria);

                    $referWeight = $this->queryService->getRepository('AMZProfileBundle:WeightForAge')
                        ->findOneBy($criteria);
                    if (!empty($referWeight)) {
                        $result['bmi'] = $referWeight->getMedian();
                        $result['bmi_from'] = $referWeight->getN1sd();
                        $result['bmi_to'] = $referWeight->getP2sd();
                    } else {
                        $result['bmi'] = '';
                        $result['bmi_from'] = '';
                        $result['bmi_to'] = '';
                    }
                    if (!empty($weightStandard) && !empty($lengthStandard)) {
                        $_3sd = $weightStandard->getN3sd();
                        $_2sd = $weightStandard->getN2sd();
                        $_1sd = $weightStandard->getN1sd();
                        $median = $weightStandard->getMedian();
                        $P3sd = $weightStandard->getP3sd();
                        $P2sd = $weightStandard->getP2sd();
                        $P1sd = $weightStandard->getP1sd();

                        $length_3sd = $lengthStandard->getN3sd();
                        $length_2sd = $lengthStandard->getN2sd();
                        $length_1sd = $lengthStandard->getN1sd();
                        $length_median = $lengthStandard->getMedian();
                        $length_P3sd = $lengthStandard->getP3sd();
                        $length_P2sd = $lengthStandard->getP2sd();
                        $length_P1sd = $lengthStandard->getP1sd();

                        $result['heightMedian'] = $length_median;
                        $result['height_from'] = $length_1sd;
                        $result['height_to'] = $length_P2sd;
                        //  -2SD ≤ CN/CC ≤ -1SD và  CC/T < - 3SD
                        switch (true) {
                            case $weight < $_3sd: //CN/CC < -3SD
                                if ($length < $length_3sd) { //CC/T < -3SD
                                    $resultType = "5.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //-3SD ≤CC/T < -2SD
                                    $resultType = "5.3";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //-2SD ≤ CC/T ≤  -1SD
                                    $resultType = "6.1";
                                } else if ($length > $length_1sd) { //- 1SD < CC/T
                                    $resultType = "7.1";
                                }
                                break;
                            case $weight >= $_3sd && $weight < $_2sd: //-3SD ≤ CN/CC < -2SD
                                if ($length < $length_3sd) { //CC/T < -3SD
                                    $resultType = "5.2";
                                } else if ($length >= $length_3sd && $length < $length_2sd){ //-3SD ≤CC/T < -2SD
                                    $resultType = "5.4";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //-2SD ≤ CC/T  ≤  -1SD
                                    $resultType = "6.2";
                                } else if ($length > $length_1sd) { //- 1SD < CC/T
                                    $resultType = "7.2";
                                }
                                break;
                            case $weight >= $_2sd && $weight <= $_1sd: //-2SD ≤ CN/CC  ≤ -1SD
                                //-2SD ≤ CC/T ≤  -1SD
                                if ($length >= $length_2sd && $length <= $length_1sd) {
                                    $resultType = "2";
                                } else if ($length > $length_1sd) { //- 1SD  < CC/T
                                    $resultType = "4";
                                } else if ($length < $length_3sd) { //CC/T < - 3SD
                                    $resultType = "8.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //-3SD ≤CC/T < -2SD
                                    $resultType = "8.2";
                                }
                                break;
                            case $weight > $_1sd && $weight <= $P2sd: // -1SD < CN/CC ≤ + 2SD
                                if ($length > $length_1sd) {
                                    $resultType = "1";
                                } else if ($length_2sd <= $length && $length <= $length_1sd) { //-2SD ≤ CC/T ≤  -1SD
                                    $resultType = "3";
                                } else if ($length < $length_3sd) { //-1SD < CN/CC ≤ + 2SD và CC/T < - 3SD
                                    $resultType = "9.1";
                                } else if ($length >= $length_3sd &&  $length < $length_2sd) { //-1SD < CN/CC ≤ + 2SD và -3SD ≤CC/T < -2SD
                                    $resultType = "9.2";
                                }
                                break;
                            case $weight > $P2sd && $weight <= $P3sd: //+2SD < CN/CC≤ +3SD
                                if ($length < $length_3sd) { //+2SD < CN/CC≤ +3SD  và  CC/T < -3SD
                                    $resultType = "10.1";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { // +2SD < CN/CC≤ +3SD   và      -3SD ≤CC/T < -2SD
                                    $resultType = "10.3";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //+2SD < CN/CC ≤ +3SD   và   -2SD ≤ CC/T ≤  -1SD
                                    $resultType = "11.1";
                                } else if ($length > $length_1sd) { //+2SD < CN/CC≤ +3SD      và            -1 SD < CC/T
                                    $resultType = "12.1";
                                }
                                break;
                            case $weight > $P3sd:
                                if ($length < $length_3sd) { //CN/CC > +3SD    và     CC/T < -3SD
                                    $resultType = "10.2";
                                } else if ($length >= $length_3sd && $length < $length_2sd) { //CN/CC > +3SD và -3SD ≤CC/T < -2SD
                                    $resultType = "10.4";
                                } else if ($length >= $length_2sd && $length <= $length_1sd) { //CN/CC > +3SD  và  -2SD ≤ CC/T ≤  -1SD
                                    $resultType = "11.2";
                                } else if ($length > $length_1sd) { //CN/CC > +3SD  và   -1 SD < CC/T
                                    $resultType = "12.2";
                                }
                                break;
                            default:
                                break;
                        }
                        //echo $resultType;die();
                    }
                } else if ($category == _5_19_YO_CATEGORY) {
                    $criteria = array(
                        'month' => $month,
                        'gender' => $gender
                    );
                    $bmiStandard = $this->queryService->getRepository('AMZProfileBundle:BmiForAge')
                        ->findOneBy($criteria);
                    $heightStandard = $this->queryService->getRepository('AMZProfileBundle:HeightForAge')
                        ->findOneBy($criteria);
                    if (!empty($bmiStandard) && !empty($heightStandard)) {
                        $bmiMedian = $bmiStandard->getMedian()*($length/100)*($length/100);
                        $result['bmi'] = $bmiMedian;
                        $bmiValue = $bmiMedian;
                        $bmi_3sd = $bmiStandard->getN3sd()*($length/100)*($length/100);
                        $bmi_2sd = $bmiStandard->getN2sd()*($length/100)*($length/100);
                        $bmi_1sd = $bmiStandard->getN1sd()*($length/100)*($length/100);
                        $bmi1sd = $bmiStandard->getP1sd()*($length/100)*($length/100);
                        $bmi2sd = $bmiStandard->getP2sd()*($length/100)*($length/100);
                        $bmi3sd = $bmiStandard->getP3sd()*($length/100)*($length/100);
                        $result['bmi_from'] = $bmi_2sd;
                        $result['bmi_to'] = $bmi1sd;
                        $heightMedian = $heightStandard->getMedian();
                        $height_3sd = $heightStandard->getN3sd();
                        $height_2sd = $heightStandard->getN2sd();
                        $height_1sd = $heightStandard->getN1sd();
                        $height1sd = $heightStandard->getP1sd();
                        $height2sd = $heightStandard->getP2sd();
                        $height3sd = $heightStandard->getP3sd();
                        $result['heightMedian'] = $heightMedian;
                        $result['height_from'] = $height_2sd;
                        $result['height_to'] = $height2sd;
                        switch (true) {
                            case $weight >= $bmi_1sd && $weight <= $bmi1sd: //-1SD ≤ BMI theo tuổi và giới ≤ +1SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 1;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 2;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 8;
                                }
                                break;
                            case $weight >= $bmi_2sd && $weight < $bmi_1sd: //-2SD ≤ BMI theo tuổi và giới < -1SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 3;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 4;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 9;
                                }
                                break;
                            case $weight < $bmi_2sd: //BMI theo tuổi và giới < -2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 5;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 6;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 7;
                                }
                                break;
                            case $weight > $bmi1sd && $weight <= $bmi2sd: //+1SD < BMI theo tuổi và giới ≤ +2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 10;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 11;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 12;
                                }
                                break;
                            case $weight > $bmi2sd: //BMI theo tuổi và giới > +2SD
                                if ($length >= $heightMedian) { //Median ≤ CC/T
                                    $resultType = 13;
                                } else if ($length >= $height_2sd && $length < $heightMedian) { //-2SD ≤ CC/T < Median
                                    $resultType = 14;
                                } else if ($length < $height_2sd) { //CC/T < -2SD
                                    $resultType = 15;
                                }
                                break;
                            default:
                                $resultType = 0;
                                break;
                        }
                    }
                } else if ($category == _over_19_YO_CATEGORY) {
                    $bmi = round($weight/(($length/100)*($length/100)),1);
                    //var_dump($bmi);die();
                    //$bmiValue = $bmi;
                    if ($bmi < 18.5) {
                        $resultType = 1;
                    } else if ($bmi >= 18.5 && $bmi <= 24.9) {
                        $resultType = 2;
                    } else if ($bmi == 25) {
                        $resultType = 3;
                    } else if ($bmi > 25 && $bmi <= 29.9) {
                        $resultType = 4;
                    } else if ($bmi >=30 && $bmi <=34.9) {
                        $resultType = 5;
                    } else if ($bmi >= 35 && $bmi <= 39.9) {
                        $resultType = 6;
                    } else if ($bmi >= 40) {
                        $resultType = 7;
                    }

                }
                //echo $resultType;die();
                $bmiResult = $this->getBmiResult($category, $subCategory, $resultType);
                //echo 'Category: '.$category.'<br>Sub category: '.$subCategory.'Result type: '.$resultType.'Gender: '.$gender;
                //echo "<pre>";echo count($bmiResult);die();
                if (!empty($bmiResult)) {
                    $result['result'] = $bmiResult->getResult();
                    $result['resultValue'] = $bmiResult->getResultValue();
                    $result['advise'] = $bmiResult->getAdvise();
                    $result['recommend'] = $bmiResult->getRecommend();

                    $oProfileBmiResult = new UserProfileBmiResult();
                    $oProfileBmiResult->setAdvise($result['advise']);
                    $oProfileBmiResult->setGender($gender);
                    $oProfileBmiResult->setRecommend($result['recommend']);
                    $oProfileBmiResult->setProfile($oProfile);
                    $oProfileBmiResult->setResult($result['result']);
                    $oProfileBmiResult->setResultValue($result['resultValue']);
                    $oProfileBmiResult->setResultType($resultType);
                    $oProfileBmiResult->setSubCategory($subCategory);
                    $oProfileBmiResult->setCategory($category);
                    if (!empty($dayWeight)) {
                        $date = date_create_from_format('d/m/Y H:i:s', $dayWeight . ' 00:00:00');
                        $oProfileBmiResult->setMeasureDate($date);
                    }else{
//                        Hoàng Lê add measure date - 14 Dec, 2016 11:28AM
                        $oProfileBmiResult->setMeasureDate(new \DateTime());
                    }

                    $oProfileBmiResult->setLength($length);
                    $oProfileBmiResult->setWeight($weight);

//                    Hoàng Lê: add calculate bmi
                    $bmiValue = $weight/(($length/100)*($length/100));
                    $oProfileBmiResult->setBmi($bmiValue);

                    $this->queryService->getRepository('AMZProfileBundle:ProfileBmiResult')
                        ->insert($oProfileBmiResult);

                }
                //echo "<pre>";//die();
                //var_dump($result);die();
                return $result;
            }
        } catch(\Exception $e){
            var_dump($e->getMessage(), 'calculate');die();
            return NULL;
        }

    }


    public function getBmiResult($category, $subCategory, $resultType) {
        try {
            if ($category == _5_19_YO_CATEGORY || $category == _0_5_YO_CATEGORY)
                $condition = array(
                'category' => $category,
                'subCategory'=> $subCategory,
                'resultType' => $resultType,
            );
            else $condition = array(
                'category' => $category,
                'resultType' => $resultType,
            );
            $bmiResult = $this->queryService->getRepository('AMZProfileBundle:BmiResult')
                ->findOneBy($condition);
            //if (!empty($bmiResult)) $bmiResult['recommend']
            return $bmiResult;
        } catch (\Exception $e) {
            var_dump($e->getMessage());die();
            return NULL;
        }
    }

    private function getCategory($birthday) {
        //echo $birthday;die();
        if (is_string($birthday)) {
            $birthday = new \DateTime($birthday);
        }
        $now = new \DateTime();
        $month = $now->diff($birthday)->format("%y")*12+$now->diff($birthday)->format("%m");
        $day = $now->diff($birthday)->format("%d");
        $category = 0;
        $subCategory = 0;
        if ($month <= 60) {
            $category = _0_5_YO_CATEGORY;
            switch (true) {
                case $month < 6:
                    $subCategory = _0_6_M_SUBCATEGORY;
                    break;
                case $month < 12:
                    $subCategory = _6_12_M_SUBCATEGORY;
                    break;
                case $month < 24:
                    $subCategory = _12_24_M_SUBCATEGORY;
                    break;
                default:
                    $subCategory = _24_60_M_SUBCATEGORY;
                    break;
            }
        } else if ($month < 228) {
            $category = _5_19_YO_CATEGORY;
            $subCategory = 1;
        } else {
            $category = _over_19_YO_CATEGORY;
        }
        //echo $month;die();
        return array($category, $subCategory, $month);
    }

    public function getMonthNo($birthday, $measureDate){
//        $now = new \DateTime();
        return $measureDate->diff($birthday)->format("%y")*12+ $measureDate->diff($birthday)->format("%m");
        return $measureDate->diff($birthday)->format("%y")*12+ $measureDate->diff($birthday)->format("%m");
    }
    
    public function roundHeight($height) {
        $_ceil = ceil($height);
        if (($_ceil-$height) == 0.5) return $height;
        else return round($height);
    }


}