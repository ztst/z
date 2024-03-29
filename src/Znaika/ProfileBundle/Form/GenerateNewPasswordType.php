<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class GenerateNewPasswordType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('password', 'password');
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\ProfileBundle\Entity\User'
            ));
        }

        public function getName()
        {
            return 'generate_new_password';
        }
    }