<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content\Quiz\Attempt;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    class UserAttemptType extends AbstractType
    {
        /**
         * @var Video
         */
        private $video;

        public function __construct(Video $video)
        {
            $this->video = $video;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('userQuestionAnswers', 'collection', array(
                        'type' => new UserQuestionAnswerType(),
                    )
                );
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
