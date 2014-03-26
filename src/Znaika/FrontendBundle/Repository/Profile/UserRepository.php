<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserRepository extends BaseRepository implements IUserRepository
    {
        /**
         * @var IUserRepository
         */
        protected $dbRepository;

        /**
         * @var IUserRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\User');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param User $user
         *
         * @return bool
         */
        public function save(User $user)
        {
            $this->redisRepository->save($user);
            $success = $this->dbRepository->save($user);

            return $success;
        }

        /**
         * @param $userId
         *
         * @return User|null
         */
        public function getOneByUserId($userId)
        {
            $result = $this->redisRepository->getOneByUserId($userId);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByUserId($userId);
            }

            return $result;
        }

        /**
         * @param $vkId
         *
         * @return User
         */
        public function getOneByVkId($vkId)
        {
            $result = $this->redisRepository->getOneByVkId($vkId);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByVkId($vkId);
            }

            return $result;
        }

        /**
         * @param $facebookId
         *
         * @return User
         */
        public function getOneByFacebookId($facebookId)
        {
            $result = $this->redisRepository->getOneByFacebookId($facebookId);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByFacebookId($facebookId);
            }

            return $result;
        }

        /**
         * @param $odnoklassnikiId
         *
         * @return User
         */
        public function getOneByOdnoklassnikiId($odnoklassnikiId)
        {
            $result = $this->redisRepository->getOneByOdnoklassnikiId($odnoklassnikiId);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByOdnoklassnikiId($odnoklassnikiId);
            }

            return $result;
        }

        /**
         * @param string $searchString
         *
         * @return User[]|null
         */
        public function getUsersBySearchString($searchString)
        {
            $result = $this->redisRepository->getUsersBySearchString($searchString);
            if (empty($result))
            {
                $result = $this->dbRepository->getUsersBySearchString($searchString);
            }

            return $result;
        }

        /**
         * @param string $email
         *
         * @return User|null
         */
        public function getOneByEmail($email)
        {
            $result = $this->redisRepository->getOneByEmail($email);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByEmail($email);
            }

            return $result;
        }

        /**
         * @param integer $limit
         *
         * @return User[]
         */
        public function getUsersTopByPoints($limit)
        {
            $result = $this->redisRepository->getUsersTopByPoints($limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getUsersTopByPoints($limit);
            }

            return $result;
        }

        public function getNotVerifiedUsers($userRoles = array())
        {
            $result = $this->redisRepository->getNotVerifiedUsers($userRoles);
            if (is_null($result))
            {
                $result = $this->dbRepository->getNotVerifiedUsers($userRoles);
            }

            return $result;
        }

        public function countNotVerifiedUsers($userRoles = array())
        {
            $result = $this->redisRepository->countNotVerifiedUsers($userRoles);
            if (is_null($result))
            {
                $result = $this->dbRepository->countNotVerifiedUsers($userRoles);
            }

            return $result;
        }

        /**
         * @param $userIds
         *
         * @return User[]
         */
        public function getByUserIds($userIds)
        {
            $result = $this->redisRepository->getByUserIds($userIds);
            if (is_null($result))
            {
                $result = $this->dbRepository->getByUserIds($userIds);
            }

            return $result;
        }
    }