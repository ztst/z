<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Entity\Profile\Badge\BaseUserBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBadgeType;
    use Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository;

    class BadgeExtension extends \Twig_Extension
    {
        /**
         * @var UserBadgeRepository
         */
        private $badgeRepository;

        /**
         * @var \Twig_Environment
         */
        private $twig;

        public function __construct(\Twig_Environment $twig, UserBadgeRepository $badgeRepository)
        {
            $this->twig            = $twig;
            $this->badgeRepository = $badgeRepository;
        }

        public function getFunctions()
        {
            return array(
                'user_badges' => new \Twig_Function_Method($this, 'renderBlock'),
                'badge_name'  => new \Twig_Function_Method($this, 'renderBadgeName'),
            );
        }

        /**
         * @param User $user
         *
         * @return string
         */
        public function renderBlock(User $user)
        {
            $result = "";
            if ($user)
            {
                $badges          = $this->badgeRepository->getUserNotViewedBadges($user);
                $templateFile    = "ZnaikaFrontendBundle:TwigExtension:user_badges.html.twig";
                $templateContent = $this->twig->loadTemplate($templateFile);

                $result = $templateContent->render(array("badges" => $badges));
            }

            return $result;
        }

        public function renderBadgeName(BaseUserBadge $badge)
        {
            return UserBadgeType::getTextByType($badge->getBadgeType());
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_badge';
        }
    }
