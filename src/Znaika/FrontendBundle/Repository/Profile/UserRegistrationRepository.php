<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class UserRegistrationRepository extends BaseRepository implements IUserRegistrationRepository
    {
        /**
         * @var IUserRegistrationRepository
         */
        protected $dbRepository;

        /**
         * @var IUserRegistrationRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserRegistrationRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\UserRegistration');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function save(UserRegistration $userRegistration)
        {
            $this->redisRepository->save($userRegistration);
            $success = $this->dbRepository->save($userRegistration);

            return $success;
        }

        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function delete(UserRegistration $userRegistration)
        {
            $this->redisRepository->delete($userRegistration);
            $success = $this->dbRepository->delete($userRegistration);

            return $success;
        }

        /**
         * @param $key
         *
         * @return UserRegistration|null
         */
        public function getOneByRegisterKey($key)
        {
            $result = $this->redisRepository->getOneByRegisterKey($key);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByRegisterKey($key);
            }

            return $result;
        }

    }