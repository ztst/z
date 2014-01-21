<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationReferralOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Repository\Profile\Action\UserOperationRepository;
    use Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository;

    class UserOperationListener
    {
        /**
         * @var UserOperationRepository
         */
        protected $userOperationRepository;

        /**
         * @var UserBadgeRepository
         */
        protected $userBadgeRepository;

        /**
         * @param UserOperationRepository $userOperationRepository
         * @param \Znaika\FrontendBundle\Repository\Profile\Badge\UserBadgeRepository $userBadgeRepository
         */
        public function __construct(UserOperationRepository $userOperationRepository, UserBadgeRepository $userBadgeRepository)
        {
            $this->userOperationRepository = $userOperationRepository;
            $this->userBadgeRepository     = $userBadgeRepository;
        }

        /**
         * @param User $user
         */
        public function onEditProfile(User $user)
        {
            $handler = new EditProfileHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);
            $handler->handle();
        }

        /**
         * @param User $user
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation
         */
        public function onRegistration(User $user)
        {
            $handler = new RegistrationHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         */
        public function onRegistrationReferral(User $user)
        {
            $handler = new RegistrationReferralHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);

            $handler->handle();
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation
         */
        public function onRateVideo(User $user, Video $video)
        {
            $handler = new RateVideoHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);
            $handler->setVideo($video);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation
         */
        public function onAddVideoComment(User $user, Video $video)
        {
            $handler = new AddVideoCommentHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);
            $handler->setVideo($video);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param $socialNetwork
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation
         */
        public function onJoinToSocialNetworkCommunity(User $user, $socialNetwork)
        {
            $handler = new JoinToSocialNetworkCommunityHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);
            $handler->setSocialNetwork($socialNetwork);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation
         */
        public function onViewVideo(User $user, Video $video)
        {
            $handler = new ViewVideoHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);
            $handler->setVideo($video);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         * @param $socialNetwork
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\PostVideoToSocialNetworkOperation
         */
        public function onPostVideoToSocialNetwork(User $user, Video $video, $socialNetwork)
        {
            $handler = new PostVideoToSocialNetworkHandler($this->userOperationRepository, $this->userBadgeRepository);
            $handler->setUser($user);
            $handler->setVideo($video);
            $handler->setSocialNetwork($socialNetwork);

            $operation = $handler->handle();

            return $operation;
        }
    }