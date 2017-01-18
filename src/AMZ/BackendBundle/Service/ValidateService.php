<?php

namespace AMZ\BackendBundle\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function email($value)
    {
        $constraint = new Assert\Email();
        $constraint->message = 'Email không hợp lệ';

        $errors = $this->validator->validate(
            $value,
            $constraint
        );
        if (0 == count($errors)) {
            return true;
        } else {
            //return $errors[0]->getMessage();
            return false;
        }
    }

    public function notNull($value)
    {
        $constraint = new Assert\NotNull();
        $constraint->message = 'Giá trị bị rỗng';

        $errors = $this->validator->validate(
            $value,
            $constraint
        );
        if (0 == count($errors)) {
            return true;
        } else {
            return false;
        }
    }

    public function isNumber($value)
    {
        $constraint = new Assert\Range(array(
            'min' => 1
        ));

        $errors = $this->validator->validate(
            $value,
            $constraint
        );
        if (0 == count($errors)) {
            return true;
        } else {
            return false;
        }
    }

    public function notBlank($value)
    {
        $constraint = new Assert\NotBlank();
        $constraint->message = 'Giá trị bị rỗng';

        $errors = $this->validator->validate(
            $value,
            $constraint
        );
        if (0 == count($errors)) {
            return true;
        } else {
            return false;
        }
    }

    public function importDateFormat($value)
    {
        $date = date_create_from_format('d/m/Y', $value);
        if (false == $date) {
            return false;
        }
        $temp = explode('/', $value);
        if ((int)$temp[0] != (int)date_format($date, 'd')) {
            return false;
        }
        if ((int)$temp[1] != (int)date_format($date, 'm')) {
            return false;
        }
        if ((int)$temp[2] != (int)date_format($date, 'Y')) {
            return false;
        }
        return true;
    }
}