<?
    namespace Znaika\FrontendBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Znaika\FrontendBundle\Form\User\UserType;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class RegistrationType extends AbstractType
    {
        /**
         * @var UserRepository
         */
        private $userRepository;

        function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('user', new UserType($this->userRepository));
            $builder->add('terms', 'checkbox', array('property_path' => 'termsAccepted'))
                    ->add('save', 'submit');
        }

        public function getName()
        {
            return 'registration';
        }
    }