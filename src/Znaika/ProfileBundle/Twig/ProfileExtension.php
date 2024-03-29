<?php

    namespace Znaika\ProfileBundle\Twig;

    use Znaika\ProfileBundle\Entity\User;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\ProfileBundle\Helper\Util\UserBan;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\ProfileBundle\Helper\Util\UserSex;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class ProfileExtension extends \Twig_Extension
    {
        const MAX_CHILDREN   = 15;
        const MAX_PARENTS    = 2;
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
                'ban_reason_button'         => new \Twig_Function_Method($this, 'renderBanReasonButton'),
                'user_sex'                  => new \Twig_Function_Method($this, 'renderUserSex'),
                'count_not_verified_pupils' => new \Twig_Function_Method($this, 'countNotVerifiedPupils'),
                'user_parents'              => new \Twig_Function_Method($this, 'renderUserParents'),
                'user_children'             => new \Twig_Function_Method($this, 'renderUserChildren'),
                'show_user_profile_setting' => new \Twig_Function_Method($this, 'renderShowUserProfile'),
            );
        }

        public function countNotVerifiedPupils()
        {
            /** @var UserRepository $userRepository */
            $userRepository = $this->container->get("znaika.user_repository");

            return $userRepository->countNotVerifiedUsers(array(UserRole::ROLE_USER));
        }

        public function renderBanReasonButton(User $user)
        {
            $reasons = UserBan::getAvailableTypesTexts();

            $templateFile    = 'ZnaikaProfileBundle:Default:Ban\user_ban_reason_button.html.twig';
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("reasons" => $reasons, "user" => $user));

            return $result;
        }

        public function renderUserParents(User $user)
        {
            $templateFile    = "ZnaikaProfileBundle:Default:user_parents_block.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $relations       = $user->getParentRelations();
            $result          = $templateContent->render(array("relations" => $relations, "canAdd" => count($relations) < self::MAX_PARENTS));

            return $result;
        }

        public function renderUserChildren(User $user)
        {
            $templateFile    = "ZnaikaProfileBundle:Default:user_children_block.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $relations       = $user->getChildRelations();
            $result          = $templateContent->render(array("relations" => $relations, "canAdd" => count($relations) < self::MAX_CHILDREN));

            return $result;
        }

        public function renderShowUserProfile(User $user)
        {
            $showUserProfile = $user->getUserSettings()->getShowUserPage();

            $templateFile    = 'ZnaikaProfileBundle:Default:Settings\show_user_profile_setting.html.twig';
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("showUserProfile" => $showUserProfile));

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
