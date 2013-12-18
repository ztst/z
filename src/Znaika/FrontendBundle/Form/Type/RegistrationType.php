<?
    namespace Znaika\FrontendBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Znaika\FrontendBundle\Form\User\UserType;

    class RegistrationType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('user', new UserType());
            $builder->add('terms', 'checkbox', array('property_path' => 'termsAccepted'))
                    ->add('save', 'submit');
        }

        public function getName()
        {
            return 'registration';
        }
    }