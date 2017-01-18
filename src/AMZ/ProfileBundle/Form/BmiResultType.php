<?php

namespace AMZ\ProfileBundle\Form;

use AMZ\ProfileBundle\Entity\Profile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Url;

class BmiResultType extends AbstractType
{
    public function __construct()
    {
//        $this->cities = $cities;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("gender", ChoiceType::class, array(
            'attr' => array('class' => 'form-control select2'),
            'disabled' => true,
            'choices' => array(
                '0' => 'Nữ',
                '1' => "Nam"
            )
        ))->add('result', TextType::class, array(
            'attr' => array('class' => 'form-control'),
            'disabled' => true
        ))->add('resultValue', TextType::class, array(
            'attr' => array('class' => 'form-control'),
            'disabled' => true
        ))->add('recommend',TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control cke-editor', 'rows' => 5)
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\ProfileBundle\Entity\BmiResult'
        ));
    }

    public function getName()
    {
        return 'amz_bmi_result';
    }
}