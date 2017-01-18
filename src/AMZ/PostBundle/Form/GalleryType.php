<?php

namespace AMZ\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập vào tiêu đề'
                ))
            )
        ))->add('smallSizeThumbnail', HiddenType::class, array(
            'required' => false,
            'attr' => array('class' => 'small-size-thumbnail')
        ))->add('fullSizeThumbnail', HiddenType::class, array(
            'required' => false,
            'attr' => array('class' => 'full-size-thumbnail')
        ))->add('content', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control cke-editor', 'rows' => 5)
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\PostBundle\Entity\Gallery'
        ));
    }

    public function getName()
    {
        return 'amz_post_gallery';
    }
}