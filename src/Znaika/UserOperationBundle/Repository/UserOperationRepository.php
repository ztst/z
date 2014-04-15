<?
    namespace Znaika\UserOperationBundle\Repository;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\AddLastNameInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddPhotoInProfileOperation;
    use Znaika\UserOperationBundle\Entity\BaseUserOperation;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class UserOperationRepository extends BaseRepository implements IUserOperationRepository
    {
        /**
         * @var IUserOperationRepository
         */
        protected $dbRepository;

        /**
         * @var IUserOperationRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserOperationRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaUserOperationBundle:BaseUserOperation');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        public function save(BaseUserOperation $operation)
        {
            $this->redisRepository->save($operation);
            $success = $this->dbRepository->save($operation);

            return $success;
        }

        public function getLastViewVideoOperation(User $user, Video $video)
        {
            $result = $this->redisRepository->getLastViewVideoOperation($user, $video);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastViewVideoOperation($user, $video);
            }

            return $result;
        }

        public function getLastPostVideoToSocialNetworkOperation(User $user, Video $video, $network)
        {
            $result = $this->redisRepository->getLastPostVideoToSocialNetworkOperation($user, $video, $network);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastPostVideoToSocialNetworkOperation($user, $video, $network);
            }

            return $result;
        }

        public function getLastAddFirstNameInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddFirstNameInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddFirstNameInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddPhotoInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddPhotoInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddPhotoInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddLastNameInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddLastNameInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddLastNameInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddCityInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddCityInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddCityInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddBirthdayInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddBirthdayInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddBirthdayInProfileOperation($user);
            }

            return $result;
        }

        public function getLastAddSexInProfileOperation(User $user)
        {
            $result = $this->redisRepository->getLastAddSexInProfileOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddSexInProfileOperation($user);
            }

            return $result;
        }

        public function getLastRegistrationOperation(User $user)
        {
            $result = $this->redisRepository->getLastRegistrationOperation($user);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastRegistrationOperation($user);
            }

            return $result;
        }

        public function getLastAddVideoCommentOperation(User $user, Video $video)
        {
            $result = $this->redisRepository->getLastAddVideoCommentOperation($user, $video);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastAddVideoCommentOperation($user, $video);
            }

            return $result;
        }

        public function getLastRateVideoOperation(User $user, Video $video)
        {
            $result = $this->redisRepository->getLastRateVideoOperation($user, $video);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastRateVideoOperation($user, $video);
            }

            return $result;
        }

        public function getLastJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            $result = $this->redisRepository->getLastJoinSocialNetworkCommunityOperation($user, $network);
            if (empty($result))
            {
                $result = $this->dbRepository->getLastJoinSocialNetworkCommunityOperation($user, $network);
            }

            return $result;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countViewVideoOperations(User $user)
        {
            $result = $this->redisRepository->countViewVideoOperations($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->countViewVideoOperations($user);
            }

            return $result;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countAddVideoCommentOperations(User $user)
        {
            $result = $this->redisRepository->countAddVideoCommentOperations($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->countAddVideoCommentOperations($user);
            }

            return $result;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countRateVideoOperations(User $user)
        {
            $result = $this->redisRepository->countRateVideoOperations($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->countRateVideoOperations($user);
            }

            return $result;
        }

        /**
         * @param User $user
         *
         * @return integer
         */
        public function countPostVideoToSocialNetworkOperations(User $user)
        {
            $result = $this->redisRepository->countPostVideoToSocialNetworkOperations($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->countPostVideoToSocialNetworkOperations($user);
            }

            return $result;
        }

        /**
         * @param integer $limit
         *
         * @return BaseUserOperation[]
         */
        public function getNewestOperations($limit)
        {
            $result = $this->redisRepository->getNewestOperations($limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getNewestOperations($limit);
            }

            return $result;
        }
    }