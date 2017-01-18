<?php

namespace AppBundle\Form;

use AMZ\UserBundle\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonalUserProfileType extends AbstractType
{
    private $profileAvatarDirectory;
    private $fullAvatarUrl;

    public function __construct($profileAvatarDirectory, $fullAvatarUrl)
    {
        $this->profileAvatarDirectory = $profileAvatarDirectory;
        $this->fullAvatarUrl = $fullAvatarUrl;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control', 'placeholder' => 'Họ'),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Nhập họ'
                ))
            )
        ))->add('lastName', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control', 'placeholder' => 'Tên'),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Nhập tên'
                ))
            )
        ))->add('dateOfBirth', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control', 'placeholder' => 'Ngày sinh', 'id' => 'calendar'),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Nhập họ'
                ))
            )
        ))->add('gender', ChoiceType::class, array(
            'required' => true,
            'choices_as_values' => true,
            'choices' => array('Nam' => UserProfile::GENDER_MALE, 'Nữ' => UserProfile::GENDER_FEMALE),
            'attr' => array(
                'class' => 'chosen gender-select',
                'data-placeholder' => 'Giới tính',
                'tabindex' => 1,
                'style' => 'width: 100%;'
            )
        ))->add('file', FileType::class, array(
            'required' => false,
            'attr' => array(
                'style' => 'display: none;'
            ),
            'constraints' => array(
                new Image(array(
                    'mimeTypesMessage' => 'Vui lòng chọn file hình ảnh'
                ))
            )
        ));


        $builder->get('dateOfBirth')
            ->addModelTransformer(new CallbackTransformer(
                function ($date) {
                    if ($date != null) {
                        return date_format($date, 'd/m/Y');
                    } else {
                        return date('d/m/Y');
                    }
                }, function ($date) {
                    return date_create_from_format('d/m/Y H:i:s', $date . ' 00:00:00');
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\UserBundle\Entity\UserProfile'
        ));
    }

    public function getName()
    {
        return 'amz_user_profile';
    }
}