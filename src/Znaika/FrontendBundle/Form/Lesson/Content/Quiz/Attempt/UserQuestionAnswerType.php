<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Quiz\Attempt;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion;

    class UserQuestionAnswerType extends AbstractType
    {
        /**
         * @var QuizQuestion
         */
        private $quizQuestion;

        public function __construct()
        {
            //$this->quizQuestion = $quizQuestion;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            var_dump($builder->getData());
            die();
            //$this->quizQuestion->getQuizAnswers();

            $builder
                ->add('quizAnswer', 'choice', array(
                        'choices' => array(
                            '1' => 'Nashville',
                            '2'     => 'Paris',
                            '3'    => 'Berlin',
                            '4'    => 'London',
                        ),
                        'expanded' => true,
                        'multiple' => false,
                    )
                );
        }

        protected function buildChoices()
        {
            $choices          = ["test"];
//            $table2Repository = $this->getDoctrine()->getRepository('BlocMainBundle:Table2');
//            $table2Objects    = $table2Repository->findAll();
//
//            foreach ($table2Objects as $table2Obj)
//            {
//                $choices[$table2Obj->getId()] = $table2Obj->getNumero() . ' - ' . $table2Obj->getName();
//            }

            return $choices;
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
