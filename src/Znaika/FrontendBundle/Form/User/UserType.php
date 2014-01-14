<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Form;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Validator\Constraints\DateTime;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class UserType extends AbstractType
    {
        /**
         * @var UserRepository
         */
        private $userRepository;

        function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('firstName', 'text')
                ->add('lastName', 'text')
                ->add('email', 'email')
                ->add('password', 'password');

            $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPostBind'));
        }

        public function onPostBind($event)
        {
            $user = $event->getData();
            $form = $event->getForm();

            $email = isset($user['email']) ? $user['email'] : "";
            $existingUser = $this->userRepository->getOneByEmail($email);

            $hasInactiveUser = ($existingUser && $existingUser->getStatus() == UserStatus::REGISTERED);
            if ($hasInactiveUser)
            {
                $this->updateUserFromArray($existingUser, $user);
                $form->setData($existingUser);
            }
        }

        private function updateUserFromArray(User $user, $data)
        {
            $user->setCreatedTime(new \DateTime());
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setPassword($data['password']);
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
    }
