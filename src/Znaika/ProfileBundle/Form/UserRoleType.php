<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\ProfileBundle\Helper\Util\UserRole;

    class UserRoleType extends AbstractType
    {
        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $roles = UserRole::getRolesForRegisterFormSelect();
            $builder
                ->add("role", "choice", array(
                    "choices"     => $roles,
                    "multiple"    => false,
                    "empty_value" => false,
                    "required"    => true,
                ));

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

        /**
         * @return string
         */
        public function getName()
        {
            return 'user_role_type';
        }
    }
