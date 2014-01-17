<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserSex;

    class UserProfileType extends UserType
    {
        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $readonly = $options['readonly'];

            $sexTypes = UserSex::getAvailableTypesTexts();

            $builder
                ->add('firstName', 'text', array('read_only' => $readonly))
                ->add('lastName', 'text', array('read_only' => $readonly))
                ->add('email', 'email', array('read_only' => $readonly))
                ->add('city', 'entity', array(
                    'class'       => 'Znaika\FrontendBundle\Entity\Location\City',
                    'property'    => 'name',
                    'empty_value' => '',
                    'required'    => false
                ))
                ->add('sex', 'choice', array(
                    'choices' => $sexTypes
                ));

            if (!$readonly)
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
                'readonly' => false
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
