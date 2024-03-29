<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Symfony\Component\Translation\Translator;
    use Znaika\ProfileBundle\Helper\Util\TeacherExperienceUtil;
    use Znaika\ProfileBundle\Helper\Util\UserSex;

    class TeacherProfileType extends AbstractType
    {
        const MAX_YEARS_OLD = 64;

        /**
         * @var Translator
         */
        private $translator;

        function __construct(Translator $translator)
        {
            $this->translator = $translator;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $sexTypes = UserSex::getAvailableTypesTexts();
            $experienceValues = TeacherExperienceUtil::getAvailableExperienceForSelect($this->translator);

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
                ->add("region", "entity", array(
                    "class"       => 'Znaika\ProfileBundle\Entity\Region',
                    "property"    => "regionName",
                    "empty_data"  => null,
                    "empty_value" => "not_selected",
                    "required"    => false,
                ))
                ->add("teacherSubjects", "collection", array(
                    'type'         => new TeacherSubjectType(),
                    'allow_add'    => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                ))
                ->add("city", "text", array(
                    "required" => false,
                ))
                ->add("teacherAchievement", "textarea", array(
                    "required" => false,
                ))
                ->add("teacherExperience", "choice", array(
                    "choices"     => $experienceValues,
                    "multiple"    => false,
                    "empty_value" => "not_selected",
                    "required"    => false,
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
            return "teacher_profile_type";
        }
    }
