<?php

    namespace Znaika\FrontendBundle\Twig\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBan;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserSex;

    class ProfileExtension extends \Twig_Extension
    {
        /**
         * @var \Twig_Environment
         */
        private $twig;

        /**
         * @var ContainerInterface
         */
        private $container;


        public function __construct(\Twig_Environment $twig, ContainerInterface $container)
        {
            $this->twig      = $twig;
            $this->container = $container;
        }

        public function getFunctions()
        {
            return array(
                'ban_reason_select' => new \Twig_Function_Method($this, 'renderBanReasonSelect'),
                'user_sex'          => new \Twig_Function_Method($this, 'renderUserSex'),
            );
        }

        public function renderBanReasonSelect()
        {
            $reasons = UserBan::getAvailableTypesTexts();

            $templateFile    = "ZnaikaFrontendBundle:User:user_ban_reason_select.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("reasons" => $reasons));

            return $result;
        }

        public function renderUserSex(User $user)
        {
            return UserSex::getTextBySex($user->getSex());
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'profile_extension';
        }
    }
