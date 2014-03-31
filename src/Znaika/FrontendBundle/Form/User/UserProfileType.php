<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserSex;

    class UserProfileType extends AbstractType
    {
        const MAX_YEARS_OLD = 64;

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $sexTypes = UserSex::getAvailableTypesTexts();
            $grades   = ClassNumberUtil::getAvailableClassesForSelect();

            $builder
                ->add("firstName", "text", array(
                    "required" => false,
                ))
                ->add("lastName", "text", array(
                    "required" => false,
                ))
                ->add("sex", "choice", array(
                    "choices"     => $sexTypes,
                    "expanded"    => true,
                    "multiple"    => false,
                    "empty_value" => false,
                    "required"    => false,
                ))
                ->add("grade", "choice", array(
                    "choices"     => $grades,
                    "empty_data"  => null,
                    "empty_value" => "not_selected_grade",
                    "required"    => false,
                ))
                ->add("region", "text", array(
                    "required" => false,
                ))
                ->add("city", "text", array(
                    "required" => false,
                ))
                ->add("nickname", "text")
                ->add("birthDate", "birthday", array(
                    "empty_value" => array("day" => "День", "month" => "Месяц", "year" => "Год"),
                    "required"    => false,
                    "format"      => "dd MMMM yyyy",
                    "widget"      => "choice",
                    "years"       => range(date("Y"), date("Y") - self::MAX_YEARS_OLD)
                ));
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                "data_class" => "Znaika\\FrontendBundle\\Entity\\Profile\\User",
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return "user_profile_type";
        }
    }
