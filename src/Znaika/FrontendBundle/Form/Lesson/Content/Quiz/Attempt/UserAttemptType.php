<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Quiz\Attempt;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class UserAttemptType extends AbstractType
    {
        public function __construct()
        {
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('userQuestionAnswers', 'collection', array(
                        'type'         => new UserQuestionAnswerType(),
                        'allow_add'    => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'label_attr'   => array('class' => 'hidden'),
                    )
                )
                ->add('save', 'submit');
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt'
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_frontendbundle_lesson_content_quiz_attempt_userattempt';
        }
    }
