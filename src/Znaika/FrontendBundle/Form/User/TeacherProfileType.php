<?
    namespace Znaika\FrontendBundle\Form\User;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserSex;

    class TeacherProfileType extends AbstractType
    {
        const MAX_YEARS_OLD = 64;

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $sexTypes = UserSex::getAvailableTypesTexts();

            $builder
                ->add("firstName", "text", array(
                    "required" => false,
                ))
                ->add("lastName", "text", array(
                    "required" => false,
                ))
                ->add("middleName", "text", array(
                    "required" => false,
                ))
                ->add("sex", "choice", array(
                    "choices"     => $sexTypes,
                    "expanded"    => true,
                    "multiple"    => false,
                    "empty_value" => false,
                    "required"    => false,
                ))
                ->add("nickname", "text")
                ->add("birthDate", "birthday", array(
                    "empty_value" => array("day" => "День", "month" => "Месяц", "year" => "Год"),
                    "required"    => false,
                    "format"      => "dd MMMM yyyy",
                    "widget"      => "choice",
                    "years"       => range(date("Y"), date("Y") - self::MAX_YEARS_OLD)
                ))
                ->add("city", "text", array(
                    "required" => false,
                ))
                ->add("save", "submit");
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
            return "teacher_profile_type";
        }
    }
