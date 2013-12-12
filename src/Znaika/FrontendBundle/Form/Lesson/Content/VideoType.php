<?php

namespace Znaika\FrontendBundle\Form\Lesson\Content;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;

class VideoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $classes = ClassNumberUtil::getAvailableClassesForSelect();

        $builder
            ->add('name', 'text')
            ->add('grade', 'choice', array(
                'choices'   => $classes
            ))
            ->add('urlName', 'text')
            ->add('url', 'text')
            ->add('subject', 'entity', array(
                'class' => 'Znaika\FrontendBundle\Entity\Lesson\Category\Subject',
                'property' => 'name'
            ))
            ->add('save', 'submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Znaika\FrontendBundle\Entity\Lesson\Content\Video'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'znaika_frontendbundle_lesson_content_video';
    }
}
