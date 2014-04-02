<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class UserType extends AbstractType
    {
        /**
         * @var UserRepository
         */
        private $userRepository;

        /**
         * @var bool
         */
        private $autoGeneratePassword;

        function __construct(UserRepository $userRepository, $autoGeneratePassword = false)
        {
            $this->userRepository = $userRepository;
            $this->autoGeneratePassword = $autoGeneratePassword;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('email', 'email')
                ->add('password', $this->autoGeneratePassword ? 'hidden' : 'password', array(
                    'required' => !$this->autoGeneratePassword
                ));

            $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        }

        public function onPreSubmit(FormEvent $event)
        {
            $user = $event->getData();
            $form = $event->getForm();

            if ($this->autoGeneratePassword)
            {
                $user['password'] = substr(md5(rand()), 0, 8);
                $event->setData($user);
            }
            $email = isset($user['email']) ? $user['email'] : "";
            $existingUser = $this->userRepository->getOneByEmail($email);

            $hasInactiveUser = ($existingUser && $existingUser->getStatus() == UserStatus::REGISTERED);
            if ($hasInactiveUser)
            {
                $this->updateUserFromArray($existingUser, $user);
                $form->setData($existingUser);
            }
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\FrontendBundle\Entity\Profile\User'
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_frontendbundle_user_userinfo';
        }

        private function updateUserFromArray(User $user, $data)
        {
            $user->setCreatedTime(new \DateTime());
            $user->setPassword($data['password']);
        }
    }
