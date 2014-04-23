<?
    namespace Znaika\ProfileBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class TeacherSubjectType extends AbstractType
    {

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add("subject", "entity", array(
                    "class"       => 'Znaika\FrontendBundle\Entity\Lesson\Category\Subject',
                    "property"    => "name",
                    "empty_data"  => null,
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
                'data_class' => 'Znaika\ProfileBundle\Entity\TeacherSubject'
            ));
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'teacher_subject_type';
        }
    }
