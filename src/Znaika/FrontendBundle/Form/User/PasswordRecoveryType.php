<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Form\DataTransformer\EmailToUserTransformer;
    

    class PasswordRecoveryType extends AbstractType
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
            $transformer = new EmailToUserTransformer($this->userRepository);
            $builder->add(
                $builder->create('user', 'email')
                        ->addModelTransformer($transformer)
            );
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\FrontendBundle\Entity\Profile\PasswordRecovery'
            ));
        }

        public function getName()
        {
            return 'password_recovery';
        }
    }