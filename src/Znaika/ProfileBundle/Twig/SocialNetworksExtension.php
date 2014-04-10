<?php

    namespace Znaika\ProfileBundle\Twig;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\Form\FormFactory;
    use Znaika\ProfileBundle\Entity\PasswordRecovery;
    use Znaika\ProfileBundle\Form\Model\Registration;
    use Znaika\ProfileBundle\Form\Type\RegistrationType;
    use Znaika\ProfileBundle\Form\PasswordRecoveryType;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class SocialNetworksExtension extends \Twig_Extension
    {
        /**
         * @var ContainerInterface
         */
        private $container;

        /**
         * @var \Twig_Environment
         */
        private $twig;

        public function __construct(\Twig_Environment $twig, ContainerInterface $container)
        {
            $this->twig      = $twig;
            $this->container = $container;
        }


        public function getFunctions()
        {
            return array(
                'vk_button_id'                       => new \Twig_Function_Method($this, 'renderVKButtonId'),
                'facebook_button_id'                 => new \Twig_Function_Method($this, 'renderFacebookButtonId'),
                'odnoklassniki_button_id'            => new \Twig_Function_Method($this, 'renderOdnoklassnikiButtonId'),
                'twitter_button_id'                  => new \Twig_Function_Method($this, 'renderTwitterButtonId'),
                'complete_social_registration_popup' => new \Twig_Function_Method($this, 'renderCompleteSocialRegistrationPopup'),
            );
        }

        public function renderCompleteSocialRegistrationPopup()
        {
            $session            = $this->container->get('session');
            $showRegisterSocial = $session->get('showRegisterSocial', false);

            $result = "";
            if ($showRegisterSocial)
            {
                $showRegisterSocial = $session->remove('showRegisterSocial');
                /** @var FormFactory $formFactory */
                $formFactory = $this->container->get('form.factory');

                /** @var UserRepository $userRepository */
                $userRepository       = $this->container->get('znaika.user_repository');
                $registerForm         = $formFactory->create(new RegistrationType($userRepository, true),
                    new Registration());
                $passwordRecoveryForm = $formFactory->create(new PasswordRecoveryType($userRepository),
                    new PasswordRecovery());

                $templateFile    = "ZnaikaFrontendBundle:TwigExtension:complete_social_registration.html.twig";
                $templateContent = $this->twig->loadTemplate($templateFile);

                $result = $templateContent->render(array(
                    "showRegisterSocial"   => $showRegisterSocial,
                    "registerForm"         => $registerForm->createView(),
                    'passwordRecoveryForm' => $passwordRecoveryForm->createView(),
                ));
            }

            return $result;

        }

        public function renderFacebookButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::FACEBOOK);
        }

        public function renderOdnoklassnikiButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::ODNOKLASSNIKI);
        }

        public function renderTwitterButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::TWITTER);
        }

        public function renderVKButtonId($prefix)
        {
            return $this->renderSocialButtonId($prefix, SocialNetworkUtil::VK);
        }

        /**
         * @param $prefix
         * @param $type
         *
         * @return string
         */
        public function renderSocialButtonId($prefix, $type)
        {
            $result = $prefix . $type;

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_social_networks_extension';
        }
    }
