<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Quiz;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Helper\Util\Lesson\QuizQuestionUtil;

    class QuizQuestionType extends AbstractType
    {
        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $quizQuestionTypes = QuizQuestionUtil::getAvailableTypesTexts();

            $builder
                ->add('text', 'textarea')
                ->add('type', 'choice', array(
                    'choices' => $quizQuestionTypes
                ))
                ->add('quizAnswers', 'collection', array(
                        'type'         => new QuizAnswerType(),
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
                'data_class' => 'Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion'
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_frontendbundle_lesson_content_quiz_quizquestion';
        }
    }
