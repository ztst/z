<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class UserProfileType extends UserType
    {
        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $readonly = $options['readonly'];

            $builder
                ->add('firstName', 'text', array( 'read_only' => $readonly ))
                ->add('lastName', 'text', array( 'read_only' => $readonly ))
                ->add('email', 'email', array( 'read_only' => $readonly ));

            if ( !$readonly )
            {
                $builder->add('save', 'submit');
            }
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            parent::setDefaultOptions($resolver);

            $resolver->setDefaults(array(
                'readonly'   => false
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_frontendbundle_user_userprofile';
        }
    }
