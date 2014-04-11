<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Znaika\ProfileBundle\Repository\UserRepository;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\ProfileBundle\Form\DataTransformer\EmailToUserTransformer;


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
                'data_class' => 'Znaika\ProfileBundle\Entity\PasswordRecovery'
            ));
        }

        public function getName()
        {
            return 'password_recovery';
        }
    }