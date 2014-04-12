<?
    namespace Znaika\ProfileBundle\Repository\Action;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\Action\AddBirthdayInProfileOperation;
    use Znaika\ProfileBundle\Entity\Action\AddPhoneNumberInProfileOperation;
    use Znaika\ProfileBundle\Entity\Action\AddSexInProfileOperation;
    use Znaika\ProfileBundle\Entity\Action\AddVideoCommentOperation;
    use Znaika\ProfileBundle\Entity\Action\BaseUserOperation;
    use Znaika\ProfileBundle\Entity\Action\JoinSocialNetworkCommunityOperation;
    use Znaika\ProfileBundle\Entity\Action\PostVideoToSocialNetworkOperation;
    use Znaika\ProfileBundle\Entity\Action\RateVideoOperation;
    use Znaika\ProfileBundle\Entity\Action\RegistrationOperation;
    use Znaika\ProfileBundle\Entity\Action\RegistrationReferralOperation;
    use Znaika\ProfileBundle\Entity\Action\ViewVideoOperation;
    use Znaika\ProfileBundle\Entity\User;

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
         * @return AddSexInProfileOperation
         */
        public function getLastAddSexInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddRegionInProfileOperation
         */
        public function getLastAddRegionInProfileOperation(User $user);

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

        /**
         * @param integer $limit
         *
         * @return BaseUserOperation[]
         */
        public function getNewestOperations($limit);
    }