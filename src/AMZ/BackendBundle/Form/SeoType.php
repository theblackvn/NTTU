<?php

namespace AMZ\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))->add('meta_description', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control', 'style' => 'height: 181px')
        ))->add('meta_keyword', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control', 'rows' => 3)
        ))->add('facebook_title', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))->add('facebook_description', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control', 'rows' => 3)
        ))->add('facebook_thumbnail', HiddenType::class, array(
            'attr' => array('class' => 'facebook-thumbnail')
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\BackendBundle\Entity\SEO'
        ));
    }

    public function getName()
    {
        return 'amz_seo';
    }
}