<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Quiz\Attempt;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;

    class UserQuestionAnswerType extends AbstractType
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
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function ($event) use ($builder)
            {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data instanceof UserQuestionAnswer)
                {
                    $choices = array();
                    $answers = $data->getQuizQuestion()->getQuizAnswers();
                    foreach ( $answers as $answer )
                    {
                        $choices[$answer->getQuizAnswerId()] = $answer->getText();
                    }

                    $form
                        ->add('quizQuestionText', 'text')
                        ->add('quizAnswer', 'choice', array(
                                'choices' => $choices,
                                'expanded' => true,
                                'multiple' => false,
                            )
                        );
                }
            });
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer'
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_frontendbundle_lesson_content_quiz_attempt_userquestionanswer';
        }
    }
