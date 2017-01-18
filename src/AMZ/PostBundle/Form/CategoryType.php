<?php

namespace AMZ\PostBundle\Form;

use AMZ\BackendBundle\Form\SeoType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



class CategoryType extends AbstractType
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
        ))->add('slug', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập vào tiêu đề'
                ))
            )
        ))->add('sortOrder', NumberType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new Range(array(
                    'invalidMessage' => 'Vui lòng nhập  số',
                    'min' => 1,
                    'minMessage' => 'Vui lòng nhập  số lớn hơn 0'
                ))
            )
        ))->add('thumbnail', HiddenType::class, array(
            'required' => false,
            'attr' => array('class' => 'post-thumbnail')
        ))->add('isFeatured', ChoiceType::class, array(
            'required' => true,
            'choices_as_values' => true,
            'choices' => array('Không' => false, 'Có' => true),
            'attr' => array('class' => 'form-control select2')
        ))->add('excerpt', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control', 'rows' => 9)
        ))->add('content', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control cke-editor', 'rows' => 5)
        ));
        /*
                $builder->add('parent', EntityType::class, array(
                    'class' => 'AMZ\PostBundle\Entity\Category',
                    'choice_label' => 'title',
                    'required' => false,
                    'placeholder' => 'Chọn danh mục cha',
                    'attr' => array('class' => 'form-control select2'),
                    'choice' =>

                    'query_builder' => function (EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('t');
                        if (!empty($options['current_id'])) {
                            $qb->andWhere($qb->expr()->neq('t.id', $options['current_id']));
                        }
                        $qb->andWhere($qb->expr()->lt('t.level',4))
                            ->orderBy('t.id', 'ASC');
                        $qb->addOrderBy('t.parent','ASC');
                        return $qb;
                    }
                    */
       // ))->add('seo', SeoType::class, array());*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\PostBundle\Entity\Category',
            'current_id' => '',
            'constraints' => array(
                new UniqueEntity(array(
                    'fields' => array('slug'),
                    'message' => 'Giá trị này đã tồn tại trong hệ thống'
                ))
            ),
            'allow_extra_fields' => true
        ));
    }
    /*

    public function buildView( FormView $view , FormInterface $form , array $options ) {

        $choices = [];

        foreach ( $view->vars['choices'] as $choice ) {
            $choices[] = $choice->data;
        }

        $choices = $this->buildTreeChoices( $choices );

        $view->vars['choices'] = $choices;

    }

    protected function buildTreeChoices( $choices , $level = 0 ) {

        $result = array();

        foreach ( $choices as $choice ){

            $result[] = new ChoiceView(
                $choice,
                $choice->getId(),
                str_repeat( '-' , $level ) . ' ' . $choice->getTitle(),
                []
            );

            if ( !$choice->getChildren()->isEmpty() )
                $result = array_merge(
                    $result,
                    $this->buildTreeChoices( $choice->getChildren() , $level + 1 )
                );

        }

        return $result;

    }
    */

    /*public function setDefaultOptions( OptionsResolverInterface $resolver ) {
        $resolver->setDefaults(array(
            'query_builder' => function ( EntityRepository $repo ) {
                return $repo->createQueryBuilder('e')->where('e.parent IS NULL');
            }
        ));
    }*/

//    public function getParent() {
//        return 'entity';
//    }

    public function getName()
    {
        return 'amz_post_category';
    }
}