<?php

    namespace Znaika\FrontendBundle\Twig\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBan;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserSex;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

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
                'ban_reason_button'        => new \Twig_Function_Method($this, 'renderBanReasonButton'),
                'user_sex'                 => new \Twig_Function_Method($this, 'renderUserSex'),
                'count_not_verified_pupils' => new \Twig_Function_Method($this, 'countNotVerifiedPupils'),
            );
        }

        public function countNotVerifiedPupils()
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->container->get("znaika_frontend.user_repository");

            return $userRepository->countNotVerifiedUsers(array(UserRole::ROLE_USER));
        }

        public function renderBanReasonButton(User $user)
        {
            $reasons = UserBan::getAvailableTypesTexts();

            $templateFile    = "ZnaikaFrontendBundle:User:user_ban_reason_button.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("reasons" => $reasons, "user" => $user));

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
