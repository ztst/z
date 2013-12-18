<?
    namespace Znaika\FrontendBundle\Repository\User;

    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\User\UserInfo;

    class UserInfoRepository extends BaseRepository implements IUserInfoRepository
    {
        /**
         * @var IUserInfoRepository
         */
        protected $dbRepository;

        /**
         * @var IUserInfoRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserInfoRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:User\UserInfo');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param UserInfo $userInfo
         *
         * @return bool
         */
        public function save(UserInfo $userInfo)
        {
            $this->redisRepository->save($userInfo);
            $success = $this->dbRepository->save($userInfo);

            return $success;
        }

        /**
         * @param $userId
         *
         * @return UserInfo|null
         */
        public function getOneByUserId($userId)
        {
            $userInfo = $this->redisRepository->getOneByUserId($userId);
            if ( empty($userInfo) )
            {
                $userInfo = $this->dbRepository->getOneByUserId($userId);
            }
            return $userInfo;
        }
    }