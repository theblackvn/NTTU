<?php

namespace AMZ\BackendBundle\Twig;

class AMZExtension extends \Twig_Extension
{

    public function __construct()
    {
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'price'))
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('amz_time_elapsed', array($this, 'timeElapsedString'))
        );
    }

    public function timeElapsedString($datetime, $full = false)
    {
        $now = new \DateTime();
        $ago = $datetime;
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
//                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'mới tạo';
    }

    public function price($price)
    {
        return number_format($price, 0, '.', ',');
    }

    public function getName()
    {
        return 'amz_extension';
    }
}