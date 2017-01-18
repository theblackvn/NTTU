<?php

namespace AMZ\ProfileBundle\Form;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Url;

class SchoolType extends AbstractType
{
    private $cities;

    public function __construct($cities)
    {
        $this->cities = $cities;
    }

    private function getCityList()
    {
        $data = array();
        foreach ($this->cities as $city => $districts) {
            $data[$city] = $city;
        }
        return $data;
    }

    private function getDistrictsByCity($city)
    {
        $data = array();
        $districts = $this->cities[$city];
        if (!empty($districts)) {
            foreach ($districts as $district) {
                $data[$district] = $district;
            }
        }
        return $data;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập vào tên'
                ))
            )
        ))->add('phone', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))->add('email', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new Email(array(
                    'message' => 'Email không hợp lệ'
                ))
            )
        ))->add('address', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))->add('website', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new Url(array(
                    'message' => 'Website không hợp lệ'
                ))
            )
        ))->add('code', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập mã trường'
                ))
            )
        ))->add('city', ChoiceType::class, array(
            'required' => true,
            'placeholder' => 'Chọn tỉnh thành',
            'choices_as_values' => true,
            'choices' => $this->getCityList(),
            'attr' => array('class' => 'form-control select2'),
            'constraints' => array(
                new NotNull()
            )
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $districtsList = array();
            $city = $data->getCity();
            if (!empty($city)) {
                $districtsList = $this->getDistrictsByCity($city);
            }
            $form->add('district', ChoiceType::class, array(
                'required' => true,
                'choices' => $districtsList,
                'attr' => array('class' => 'form-control select2')
            ));
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $city = $data['city'];
            $districtsList = $this->getDistrictsByCity($city);
            $form->add('district', ChoiceType::class, array(
                'required' => true,
                'choices' => $districtsList,
                'attr' => array('class' => 'form-control select2')
            ));
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\ProfileBundle\Entity\School',
            'current_id' => '',
            'constraints' => array(
                new UniqueEntity(array(
                    'fields' => array('code'),
                    'message' => 'Giá trị này đã tồn tại trong hệ thống'
                ))
            )
        ));
    }

    public function getName()
    {
        return 'amz_school';
    }
}