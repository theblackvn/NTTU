<?php

namespace AMZ\PostBundle\Form;

use AMZ\BackendBundle\Form\SeoType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class TagType extends AbstractType
{
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
        ))->add('slug', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập vào slug'
                ))
            )
        ))->add('isFeatured', ChoiceType::class, array(
            'required' => true,
            'choices_as_values' => true,
            'choices' => array('Không' => false, 'Có' => true),
            'attr' => array('class' => 'form-control select2')
        ))->add('seo', SeoType::class, array());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\PostBundle\Entity\Tag',
            'current_id' => '',
            'constraints' => array(
                new UniqueEntity(array(
                    'fields' => array('slug'),
                    'message' => 'Giá trị này đã tồn tại trong hệ thống'
                ))
            )
        ));
    }

    public function getName()
    {
        return 'amz_post_tag';
    }
}