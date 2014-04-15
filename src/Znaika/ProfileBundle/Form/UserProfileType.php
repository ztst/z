<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\FrontendBundle\Helper\Util\Lesson\ClassNumberUtil;
    use Znaika\ProfileBundle\Helper\Util\UserSex;
    use Znaika\ProfileBundle\Repository\RegionRepository;

    class UserProfileType extends AbstractType
    {
        const MIN_YEARS_OLD = 14;
        const MAX_YEARS_OLD = 113;

        /**
         * @var RegionRepository
         */
        private $regionRepository;

        /**
         * @param RegionRepository $regionRepository
         */
        public function __construct(RegionRepository $regionRepository)
        {
            $this->regionRepository = $regionRepository;
        }

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $sexTypes = UserSex::getAvailableTypesTexts();
            $grades   = ClassNumberUtil::getAvailableClassesForSelect();
            $regions  = $this->regionRepository->getAll();

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
                    "empty_value" => "not_selected",
                    "required"    => false,
                ))
                ->add("region", "entity", array(
                    "class"       => "Znaika\\ProfileBundle\\Entity\\Region",
                    "property"    => "regionName",
                    "empty_data"  => null,
                    "empty_value" => "not_selected",
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
                    "years"       => range(date("Y") - self::MIN_YEARS_OLD, date("Y") - self::MAX_YEARS_OLD)
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
            return "user_profile_type";
        }
    }
