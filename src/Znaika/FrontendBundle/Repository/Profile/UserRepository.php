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
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\User');

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
            if ( empty($result) )
            {
                $result = $this->dbRepository->getOneByUserId($userId);
            }
            return $result;
        }

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getUsersBySearchString($searchString)
        {
            $result = $this->redisRepository->getUsersBySearchString($searchString);
            if ( empty($result) )
            {
                $result = $this->dbRepository->getUsersBySearchString($searchString);
            }
            return $result;
        }
    }