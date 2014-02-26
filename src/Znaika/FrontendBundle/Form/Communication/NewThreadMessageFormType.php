<?php

    namespace Znaika\FrontendBundle\Form\Communication;

    use Symfony\Component\Form\FormBuilderInterface;
    use FOS\MessageBundle\FormType\NewThreadMessageFormType as BaseNewThreadMessageFormType;
    use Symfony\Component\Form\FormEvents;

    /**
     * Message form type for starting a new conversation
     *
     * @author Thibault Duplessis <thibault.duplessis@gmail.com>
     */
    class NewThreadMessageFormType extends BaseNewThreadMessageFormType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('recipient', 'recipient_input')
                ->add('subject', 'hidden', array('data' => 'Empty'))
                ->add('body', 'textarea');
        }
    }
