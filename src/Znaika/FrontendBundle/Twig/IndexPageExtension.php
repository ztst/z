<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\FrontendBundle\Repository\Profile\Action\UserOperationRepository;
    use Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository;
    use Znaika\FrontendBundle\Repository\Profile\UserRepository;

    class IndexPageExtension extends \Twig_Extension
    {
        const MAX_NEWEST_VIDEO          = 3;
        const MAX_POPULAR_VIDEO         = 3;
        const MAX_NEWEST_USER_OPERATION = 5;
        const MAX_NEWEST_USER_BADGE     = 5;
        const MAX_USERS_TOP             = 5;

        /**
         * @var VideoRepository
         */
        private $videoRepository;

        /**
         * @var UserOperationRepository
         */
        private $userOperationRepository;

        /**
         * @var UserRepository
         */
        private $userRepository;

        /**
         * @var UserBadgeRepository
         */
        private $userBadgeRepository;

        /**
         * @var \Twig_Environment
         */
        private $twig;

        public function __construct(\Twig_Environment $twig, VideoRepository $videoRepository,
                                    UserOperationRepository $userOperationRepository, UserRepository $userRepository,
                                    UserBadgeRepository $userBadgeRepository)
        {
            $this->twig                    = $twig;
            $this->videoRepository         = $videoRepository;
            $this->userOperationRepository = $userOperationRepository;
            $this->userRepository          = $userRepository;
            $this->userBadgeRepository     = $userBadgeRepository;
        }

        public function getFunctions()
        {
            return array(
                'newest_video'          => new \Twig_Function_Method($this, 'renderNewestVideo'),
                'popular_video'         => new \Twig_Function_Method($this, 'renderPopularVideo'),
                'newest_user_operation' => new \Twig_Function_Method($this, 'renderNewestUserOperation'),
                'newest_user_badge'     => new \Twig_Function_Method($this, 'renderNewestUserBadge'),
                'top_user_by_points'    => new \Twig_Function_Method($this, 'renderUsersTop'),
            );
        }

        /**
         * @return string
         */
        public function renderNewestVideo()
        {
            $videos          = $this->videoRepository->getNewestVideo(self::MAX_NEWEST_VIDEO);
            $templateFile    = "ZnaikaFrontendBundle:Default:newest_videos.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("videos" => $videos));

            return $result;
        }

        /**
         * @return string
         */
        public function renderPopularVideo()
        {
            $videos          = $this->videoRepository->getPopularVideo(self::MAX_POPULAR_VIDEO);
            $templateFile    = "ZnaikaFrontendBundle:Default:popular_videos.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("videos" => $videos));

            return $result;
        }

        /**
         * @return string
         */
        public function renderNewestUserOperation()
        {
            $userOperations  = $this->userOperationRepository->getNewestOperations(self::MAX_NEWEST_USER_OPERATION);
            $templateFile    = "ZnaikaFrontendBundle:Default:newest_user_operations.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("userOperations" => $userOperations));

            return $result;
        }

        /**
         * @return string
         */
        public function renderUsersTop()
        {
            $users           = $this->userRepository->getUsersTopByPoints(self::MAX_USERS_TOP);
            $templateFile    = "ZnaikaFrontendBundle:Default:users_top_by_points.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("users" => $users));

            return $result;
        }

        /**
         * @return string
         */
        public function renderNewestUserBadge()
        {
            $userBadges      = $this->userBadgeRepository->getNewestBadges(self::MAX_NEWEST_USER_BADGE);
            $templateFile    = "ZnaikaFrontendBundle:Default:newest_user_badges.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("userBadges" => $userBadges));

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_index_page';
        }
    }
