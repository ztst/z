<?php

    namespace Znaika\FrontendBundle\Form\Lesson\Content;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Form\DataTransformer\EmailsToUsersTransformer;
    use Znaika\FrontendBundle\Form\DataTransformer\VideoUrlTransformer;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoUtil;
    use Znaika\FrontendBundle\Helper\Util\TransliterateUtil;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class VideoType extends AbstractType
    {
        /**
         * @var UserRepository
         */
        protected $userRepository;

        public function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $emailsTransformer   = new EmailsToUsersTransformer($this->userRepository);
            $videoUrlTransformer = new VideoUrlTransformer();

            $builder
                ->add('name', 'text')
                ->add('author', 'text')
                ->add(
                    $builder->create('url', 'text')
                            ->addModelTransformer($videoUrlTransformer)
                )
                ->add(
                    $builder->create('supervisors', 'text', array(
                        'required' => false
                    ))->addModelTransformer($emailsTransformer)
                );

            $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        }

        public function onPreSubmit($event)
        {
            $formData = $event->getData();
            $form     = $event->getForm();

            $video = $form->getData();
            if ($video instanceof Video)
            {
                $urlName = str_replace(" ", "-", TransliterateUtil::transliterateFromCyrillic($formData['name']));
                $video->setUrlName($urlName);

                $url = VideoUtil::getVimeoIdByUrl($formData['url']);
                $formData['url'] = $url;
            }
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
