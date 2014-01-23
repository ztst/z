<?
    namespace Znaika\FrontendBundle\Repository\Profile\Action;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\BaseUserOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserOperationRedisRepository implements IUserOperationRepository
    {
        public function save(BaseUserOperation $operation)
        {
            return true;
        }

        public function getLastAddCityInProfileOperation(User $user)
        {
            return null;
        }

        public function getLastViewVideoOperation(User $user, Video $video)
        {
            return null;
        }

        public function getLastPostVideoToSocialNetworkOperation(User $user, Video $video, $network)
        {
            return null;
        }

        public function getLastAddPhoneNumberInProfileOperation(User $user)
        {
            return null;
        }

        public function getLastAddBirthdayInProfileOperation(User $user)
        {
            return null;
        }

        public function getLastAddClassroomInProfileOperation(User $user)
        {
            return null;
        }

        public function getLastAddSchoolInProfileOperation(User $user)
        {
            return null;
        }

        public function getLastAddSexInProfileOperation(User $user)
        {
            return null;
        }

        public function getLastRegistrationReferralOperation(User $user)
        {
            return null;
        }

        public function getLastRegistrationOperation(User $user)
        {
            return null;
        }

        public function getLastAddVideoCommentOperation(User $user, Video $video)
        {
            return null;
        }

        public function getLastRateVideoOperation(User $user, Video $video)
        {
            return null;
        }

        public function getLastJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            return null;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countViewVideoOperations(User $user)
        {
            return null;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countAddVideoCommentOperations(User $user)
        {
            return null;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countRateVideoOperations(User $user)
        {
            return null;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countReferralRegistrationOperations(User $user)
        {
            return null;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countPostVideoToSocialNetworkOperations(User $user)
        {
            return null;
        }

        /**
         * @param integer $limit
         *
         * @return BaseUserOperation[]
         */
        public function getNewestOperations($limit)
        {
            return null;
        }
    }