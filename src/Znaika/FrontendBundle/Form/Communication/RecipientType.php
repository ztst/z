<?php
    namespace Znaika\FrontendBundle\Form\Communication;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use Znaika\ProfileBundle\Form\DataTransformer\UserToIdTransformer;

    /**
     * Description of RecipientsType
     *
     * @author Łukasz Pospiech <zocimek@gmail.com>
     */
    class RecipientType extends AbstractType
    {
        /**
         * @var UserToIdTransformer
         */
        private $userToIdTransformer;

        /**
         * @param UserToIdTransformer $transformer
         */
        public function __construct(UserToIdTransformer $transformer)
        {
            $this->userToIdTransformer = $transformer;
        }

        /**
         * {@inheritDoc}
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->addModelTransformer($this->userToIdTransformer);
        }

        /**
         * {@inheritDoc}
         */
        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'read_only' => true
            ));
        }

        /**
         * {@inheritDoc}
         */
        public function getParent()
        {
            return 'hidden';
        }

        /**
         * {@inheritDoc}
         */
        public function getName()
        {
            return 'recipient_input';
        }
    }
