<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddCityInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddSexInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\PostVideoToSocialNetworkOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\SocialNetworkUtil;
    use Znaika\FrontendBundle\Repository\Profile\Action\UserOperationRepository;

    class UserOperationProvider
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
            $this->saveAddCityInProfileOperation($user);
            $this->saveAddBirthdayInProfileOperation($user);
            $this->saveAddClassroomInProfileOperation($user);
            $this->saveAddPhoneNumberInProfileOperation($user);
            $this->saveAddSchoolInProfileOperation($user);
            $this->saveAddSexInProfileOperation($user);
        }

        /**
         * @param User $user
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation
         */
        public function onRegistration(User $user)
        {
            return $this->saveRegistrationOperation($user);
        }

        /**
         * @param User $user
         */
        public function onRegistrationReferral(User $user)
        {
            return $this->saveRegistrationReferralOperation($user);
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation
         */
        public function onRateVideo(User $user, Video $video)
        {
            return $this->saveRateVideoOperation($user, $video);
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation
         */
        public function onAddVideoComment(User $user, Video $video)
        {
            return $this->saveAddVideoCommentOperation($user, $video);
        }

        /**
         * @param User $user
         * @param $socialNetwork
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation
         */
        public function onJoinToSocialNetworkCommunity(User $user, $socialNetwork)
        {
            return $this->saveJoinSocialNetworkCommunityOperation($user, $socialNetwork);
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation
         */
        public function onViewVideo(User $user, Video $video)
        {
            return $this->saveViewVideoOperation($user, $video);
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
            return $this->savePostVideoToSocialNetworkOperation($user, $video, $socialNetwork);
        }

        /**
         * @param User $user
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\AddCityInProfileOperation
         */
        private function saveAddCityInProfileOperation(User $user)
        {
            if (!$user->getCity())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddCityInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddCityInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation
         */
        private function saveRegistrationOperation(User $user)
        {
            $operation = $this->userOperationRepository->getLastRegistrationOperation($user);
            if (!$operation)
            {
                $operation = new RegistrationOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         */
        private function saveRegistrationReferralOperation(User $user)
        {
            //TODO: add method
        }

        /**
         * @param User $user
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\AddSexInProfileOperation
         */
        private function saveAddSexInProfileOperation(User $user)
        {
            if (!$user->getSex())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddSexInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddSexInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         */
        private function saveAddSchoolInProfileOperation(User $user)
        {
            //TODO: add method
        }

        /**
         * @param User $user
         */
        private function saveAddClassroomInProfileOperation(User $user)
        {
            //TODO: add method
        }

        /**
         * @param User $user
         */
        private function saveAddBirthdayInProfileOperation(User $user)
        {
            //TODO: add method
        }

        /**
         * @param User $user
         */
        private function saveAddPhoneNumberInProfileOperation(User $user)
        {
            //TODO: add method
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation
         */
        private function saveViewVideoOperation(User $user, Video $video)
        {
            $operation = $this->userOperationRepository->getLastViewVideoOperation($user, $video);
            if (!$operation)
            {
                $operation = new ViewVideoOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation
         */
        private function saveAddVideoCommentOperation(User $user, Video $video)
        {
            $operation = $this->userOperationRepository->getLastAddVideoCommentOperation($user, $video);
            if (!$operation)
            {
                $operation = new AddVideoCommentOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation
         */
        private function saveRateVideoOperation(User $user, Video $video)
        {
            $operation = $this->userOperationRepository->getLastRateVideoOperation($user, $video);
            if (!$operation)
            {
                $operation = new RateVideoOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         * @param $network
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation
         */
        private function saveJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            $operation = $this->userOperationRepository->getLastJoinSocialNetworkCommunityOperation($user, $network);
            if (!$operation)
            {
                $operation = new JoinSocialNetworkCommunityOperation();
                $operation->setUser($user);
                $operation->setSocialNetwork($network);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         * @param $socialNetwork
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\PostVideoToSocialNetworkOperation
         */
        private function savePostVideoToSocialNetworkOperation(User $user, Video $video, $socialNetwork)
        {
            if (!SocialNetworkUtil::isValidSocialNetwork($socialNetwork))
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastPostVideoToSocialNetworkOperation($user, $video,
                $socialNetwork);
            if (!$operation)
            {
                $operation = new PostVideoToSocialNetworkOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $operation->setSocialNetwork($socialNetwork);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }
    }