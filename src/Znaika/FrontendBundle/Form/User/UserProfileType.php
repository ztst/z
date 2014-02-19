<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserSex;

    class UserProfileType extends AbstractType
    {
        const MAX_YEARS_OLD = 90;

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
                ->add('school', 'entity', array(
                    'class'       => 'Znaika\FrontendBundle\Entity\Education\School',
                    'property'    => 'name',
                    'empty_value' => '',
                    'required'    => false
                ))
                ->add('classroom', 'entity', array(
                    'class'       => 'Znaika\FrontendBundle\Entity\Education\Classroom',
                    'empty_value' => '',
                    'required'    => false
                ))
                ->add('sex', 'choice', array(
                    'choices' => $sexTypes
                ))
                ->add('birthDate', 'birthday', array(
                    'empty_value' => array('day' => 'День', 'month' => 'Месяц', 'year' => 'Год'),
                    'required'    => false,
                    'disabled'    => $readonly,
                    'format'      => 'dd MMMM yyyy',
                    'widget'      => 'choice',
                    'years'       => range(date('Y'), date('Y') - self::MAX_YEARS_OLD)
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
            $resolver->setDefaults(array(
                'data_class' => 'Znaika\FrontendBundle\Entity\Profile\User',
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
