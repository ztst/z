<?
    namespace Znaika\ProfileBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Znaika\ProfileBundle\Form\UserType;
    use Znaika\ProfileBundle\Repository\UserRepository;

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
        }

        public function getName()
        {
            return 'registration';
        }
    }