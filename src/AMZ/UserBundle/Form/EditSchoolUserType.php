<?php

namespace AMZ\UserBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class EditSchoolUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'required' => false,
            'read_only' => true,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(),
                new NotNull()
            )
        ))->add('fullName', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(),
                new NotNull()
            )
        ))->add('email', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(),
                new NotNull(),
                new Email()
            )
        ))->add('workSchools', EntityType::class, array(
            'class' => 'AMZ\ProfileBundle\Entity\School',
            'choice_label' => function($school) {
                return $school->getName() . ' - ' . $school->getDistrict() . ', ' . $school->getCity();
            },
            'required' => true,
            'multiple' => true,
            'expanded' => false,
            'placeholder' => 'Chọn trường',
            'attr' => array('class' => 'select2 select2-multiple'),
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('t');
                $qb->orderBy('t.name', 'ASC');
                return $qb;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\UserBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'amz_user_create';
    }
}