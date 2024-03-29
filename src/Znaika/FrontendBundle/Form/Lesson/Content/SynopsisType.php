<?php

namespace Znaika\FrontendBundle\Form\Lesson\Content;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SynopsisType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('msWordFile', 'file')
            ->add('htmlFile', 'file');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'znaika_frontendbundle_lesson_content_synopsis';
    }
}
