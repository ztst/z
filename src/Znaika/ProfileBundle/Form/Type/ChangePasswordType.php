<?
    namespace Znaika\ProfileBundle\Form\Type;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class ChangePasswordType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('oldPassword', 'password');
            $builder->add('newPassword', 'password');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\ProfileBundle\Form\Model\ChangePassword',
            ));
        }

        public function getName()
        {
            return 'change_password_type';
        }
    }