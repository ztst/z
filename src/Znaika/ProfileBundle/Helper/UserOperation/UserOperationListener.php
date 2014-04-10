<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\Action\RegistrationReferralOperation;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\ProfileBundle\Repository\Action\UserOperationRepository;
    use Znaika\ProfileBundle\Repository\Badge\UserBadgeRepository;

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
         * @param \Znaika\ProfileBundle\Repository\Badge\UserBadgeRepository $userBadgeRepository
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
         * @return \Znaika\ProfileBundle\Entity\Action\RegistrationOperation
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
         * @return \Znaika\ProfileBundle\Entity\Action\RateVideoOperation
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
         * @return \Znaika\ProfileBundle\Entity\Action\AddVideoCommentOperation
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
         * @return \Znaika\ProfileBundle\Entity\Action\JoinSocialNetworkCommunityOperation
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
         * @return \Znaika\ProfileBundle\Entity\Action\ViewVideoOperation
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
         * @return null|\Znaika\ProfileBundle\Entity\Action\PostVideoToSocialNetworkOperation
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