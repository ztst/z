<?
    namespace Znaika\FrontendBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvents;
    use Znaika\FrontendBundle\Form\User\UserType;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class RegistrationType extends AbstractType
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
            $this->userRepository       = $userRepository;
            $this->autoGeneratePassword = $autoGeneratePassword;
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('user', new UserType($this->userRepository, $this->autoGeneratePassword));
            $builder->add('terms', 'checkbox', array('property_path' => 'termsAccepted'))
                    ->add('save', 'submit');
        }

        public function getName()
        {
            return 'registration';
        }
    }