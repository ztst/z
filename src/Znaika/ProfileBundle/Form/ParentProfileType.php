<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\ProfileBundle\Helper\Util\UserSex;

    class ParentProfileType extends AbstractType
    {

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
                ->add("region", "entity", array(
                    "class"       => 'Znaika\ProfileBundle\Entity\Region',
                    "property"    => "regionName",
                    "empty_data"  => null,
                    "empty_value" => "not_selected",
                    "required"    => false,
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
                    "years"       => range(date("Y") - UserProfileType::MIN_YEARS_OLD, date("Y") - UserProfileType::MAX_YEARS_OLD)
                ));
        }

        /**
         * @param OptionsResolverInterface $resolver
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                "data_class" => "Znaika\\ProfileBundle\\Entity\\User",
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return "parent_profile_type";
        }
    }
