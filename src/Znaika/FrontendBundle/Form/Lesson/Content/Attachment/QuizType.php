<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Attachment;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class QuizType extends AbstractType
    {
        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('file', 'file');
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz'
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_frontendbundle_lesson_content_attachment_quiz';
        }
    }
