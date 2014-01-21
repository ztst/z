<?
    namespace Znaika\FrontendBundle\Repository\Profile\Action;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddBirthdayInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddCityInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddClassroomInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddPhoneNumberInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddSchoolInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddSexInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\BaseUserOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\PostVideoToSocialNetworkOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationReferralOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;

    interface IUserOperationRepository
    {
        /**
         * @param BaseUserOperation $operation
         *
         * @return bool
         */
        public function save(BaseUserOperation $operation);

        /**
         * @param User $user
         *
         * @return AddCityInProfileOperation
         */
        public function getLastAddCityInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddPhoneNumberInProfileOperation
         */
        public function getLastAddPhoneNumberInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddBirthdayInProfileOperation
         */
        public function getLastAddBirthdayInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddClassroomInProfileOperation
         */
        public function getLastAddClassroomInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddSchoolInProfileOperation
         */
        public function getLastAddSchoolInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddSexInProfileOperation
         */
        public function getLastAddSexInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return RegistrationReferralOperation
         */
        public function getLastRegistrationReferralOperation(User $user);

        /**
         * @param User $user
         *
         * @return RegistrationOperation
         */
        public function getLastRegistrationOperation(User $user);

        /**
         * @param User $user
         * @param Video $video
         *
         * @return ViewVideoOperation
         */
        public function getLastViewVideoOperation(User $user, Video $video);

        /**
         * @param User $user
         * @param Video $video
         *
         * @return AddVideoCommentOperation
         */
        public function getLastAddVideoCommentOperation(User $user, Video $video);

        /**
         * @param User $user
         * @param Video $video
         *
         * @return RateVideoOperation
         */
        public function getLastRateVideoOperation(User $user, Video $video);

        /**
         * @param User $user
         * @param Video $video
         * @param $network
         *
         * @return PostVideoToSocialNetworkOperation
         */
        public function getLastPostVideoToSocialNetworkOperation(User $user, Video $video, $network);

        /**
         * @param User $user
         * @param $network
         *
         * @return JoinSocialNetworkCommunityOperation
         */
        public function getLastJoinSocialNetworkCommunityOperation(User $user, $network);

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countViewVideoOperations(User $user);

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countAddVideoCommentOperations(User $user);

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countRateVideoOperations(User $user);

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countReferralRegistrationOperations(User $user);

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countPostVideoToSocialNetworkOperations(User $user);
    }