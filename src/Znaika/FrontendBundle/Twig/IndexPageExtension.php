<?php

    namespace Znaika\FrontendBundle\Twig;

    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;
    use Znaika\ProfileBundle\Repository\Action\UserOperationRepository;
    use Znaika\ProfileBundle\Repository\Badge\UserBadgeRepository;
    use Znaika\ProfileBundle\Repository\UserRepository;

    class IndexPageExtension extends \Twig_Extension
    {
        const MAX_NEWEST_VIDEO  = 8;
        const MAX_POPULAR_VIDEO = 8;

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
        public function getName()
        {
            return 'znaika_index_page';
        }
    }
