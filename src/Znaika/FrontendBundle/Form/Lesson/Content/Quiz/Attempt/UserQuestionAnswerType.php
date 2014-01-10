<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Quiz\Attempt;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;
    use Znaika\FrontendBundle\Helper\Util\Lesson\QuizQuestionUtil;

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

            $builder->add('quizQuestion', 'entity', array(
                'class'    => 'Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion',
                'property' => 'text',
            ));

            $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        }

        public function onPreSetData($event)
        {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data instanceof UserQuestionAnswer)
            {
                $this->addQuizAnswersField($form, $data);
            }
        }

        private function addQuizAnswersField($form, UserQuestionAnswer $data = null)
        {
            $params = array(
                'class'    => 'Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer',
                'property' => 'text',
                'expanded' => true,
            );
            if ($data)
            {
                $params['choices']  = $data->getQuizQuestion()->getQuizAnswers();
                $params['multiple'] = $data->getQuizQuestion()->getType() == QuizQuestionUtil::MULTIPLE_CHOICE;
            }
            $form->add('quizAnswers', 'entity', $params);
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
