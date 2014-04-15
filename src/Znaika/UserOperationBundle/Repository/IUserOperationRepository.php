<?
    namespace Znaika\UserOperationBundle\Repository;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\AddBirthdayInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddCityInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddFirstNameInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddLastNameInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddPhotoInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddSexInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddVideoCommentOperation;
    use Znaika\UserOperationBundle\Entity\BaseUserOperation;
    use Znaika\UserOperationBundle\Entity\JoinSocialNetworkCommunityOperation;
    use Znaika\UserOperationBundle\Entity\PostVideoToSocialNetworkOperation;
    use Znaika\UserOperationBundle\Entity\RateVideoOperation;
    use Znaika\UserOperationBundle\Entity\RegistrationOperation;
    use Znaika\UserOperationBundle\Entity\ViewVideoOperation;
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
         * @return AddFirstNameInProfileOperation
         */
        public function getLastAddFirstNameInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddLastNameInProfileOperation
         */
        public function getLastAddLastNameInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddCityInProfileOperation
         */
        public function getLastAddCityInProfileOperation(User $user);

        /**
         * @param User $user
         *
         * @return AddPhotoInProfileOperation
         */
        public function getLastAddPhotoInProfileOperation(User $user);

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
        public function countPostVideoToSocialNetworkOperations(User $user);

        /**
         * @param integer $limit
         *
         * @return BaseUserOperation[]
         */
        public function getNewestOperations($limit);
    }