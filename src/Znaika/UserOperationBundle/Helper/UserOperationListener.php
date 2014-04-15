<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\UserOperationBundle\Repository\UserOperationRepository;

    class UserOperationListener
    {
        /**
         * @var UserOperationRepository
         */
        protected $userOperationRepository;

        /**
         * @param UserOperationRepository $userOperationRepository
         */
        public function __construct(UserOperationRepository $userOperationRepository)
        {
            $this->userOperationRepository = $userOperationRepository;
        }

        /**
         * @param User $user
         */
        public function onEditProfile(User $user)
        {
            $handler = new EditProfileHandler($this->userOperationRepository);
            $handler->setUser($user);
            $handler->handle();
        }

        /**
         * @param User $user
         *
         * @return \Znaika\UserOperationBundle\Entity\RegistrationOperation
         */
        public function onRegistration(User $user)
        {
            $handler = new RegistrationHandler($this->userOperationRepository);
            $handler->setUser($user);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         */
        public function onRegistrationReferral(User $user)
        {
            $handler = new RegistrationReferralHandler($this->userOperationRepository);
            $handler->setUser($user);

            $handler->handle();
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\UserOperationBundle\Entity\RateVideoOperation
         */
        public function onRateVideo(User $user, Video $video)
        {
            $handler = new RateVideoHandler($this->userOperationRepository);
            $handler->setUser($user);
            $handler->setVideo($video);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\UserOperationBundle\Entity\AddVideoCommentOperation
         */
        public function onAddVideoComment(User $user, Video $video)
        {
            $handler = new AddVideoCommentHandler($this->userOperationRepository);
            $handler->setUser($user);
            $handler->setVideo($video);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param $socialNetwork
         *
         * @return \Znaika\UserOperationBundle\Entity\JoinSocialNetworkCommunityOperation
         */
        public function onJoinToSocialNetworkCommunity(User $user, $socialNetwork)
        {
            $handler = new JoinToSocialNetworkCommunityHandler($this->userOperationRepository);
            $handler->setUser($user);
            $handler->setSocialNetwork($socialNetwork);

            $operation = $handler->handle();

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\UserOperationBundle\Entity\ViewVideoOperation
         */
        public function onViewVideo(User $user, Video $video)
        {
            $handler = new ViewVideoHandler($this->userOperationRepository);
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
         * @return null|\Znaika\UserOperationBundle\Entity\PostVideoToSocialNetworkOperation
         */
        public function onPostVideoToSocialNetwork(User $user, Video $video, $socialNetwork)
        {
            $handler = new PostVideoToSocialNetworkHandler($this->userOperationRepository);
            $handler->setUser($user);
            $handler->setVideo($video);
            $handler->setSocialNetwork($socialNetwork);

            $operation = $handler->handle();

            return $operation;
        }
    }