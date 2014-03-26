<?php

    namespace Znaika\FrontendBundle\Twig\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBan;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use Znaika\FrontendBundle\Twig\SecurityExtension;

    class UserBanExtension extends \Twig_Extension
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
                'user_can_write_comment'     => new \Twig_Function_Method($this, 'canWriteComment'),
                'user_can_pass_test'         => new \Twig_Function_Method($this, 'canPassTest'),
                'is_banned_user'             => new \Twig_Function_Method($this, 'isBannedUser'),
                'comment_ban_remaining_time' => new \Twig_Function_Method($this, 'commentBanRemainingTime'),
            );
        }

        public function commentBanRemainingTime(User $user)
        {
            $updatedTime = $user->getUpdatedTime();
            $updatedTime->add(new \DateInterval(UserBan::COMMENT_BAN_TIME));
            $interval = $updatedTime->diff(new \DateTime());
            return $interval->format('%h ч %i мин');
        }

        public function canWriteComment()
        {
            $securityExtension = $this->getSecurityExtension();
            if (!$securityExtension->isUser())
            {
                return false;
            }

            return !$this->isBannedUser();
        }

        public function canPassTest()
        {
            $securityExtension = $this->getSecurityExtension();
            if (!$securityExtension->isUser())
            {
                return false;
            }

            $user       = $this->getUser();

            $disabledViewQuiz = UserBan::isPermanentlyBanned($user)
                || UserBan::isProfileBanned($user);

            return !$disabledViewQuiz;
        }

        public function isBannedUser()
        {
            $user = $this->getUser();

            return $user ? UserBan::isBanned($user) : false;
        }


        /**
         * @return string
         */
        public function getName()
        {
            return 'user_ban_extension';
        }

        /**
         * @return User
         */
        private function getUser()
        {
            $user = $this->container->get("security.context")->getToken()->getUser();

            return ($user instanceof User) ? $user : null;
        }

        /**
         * @return SecurityExtension
         */
        private function getSecurityExtension()
        {
            return $this->container->get("znaika_frontend.security_extension");
        }
    }
