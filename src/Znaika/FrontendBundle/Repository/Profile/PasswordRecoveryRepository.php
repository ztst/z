<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class PasswordRecoveryRepository extends BaseRepository implements IPasswordRecoveryRepository
    {
        /**
         * @var IPasswordRecoveryRepository
         */
        protected $dbRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new PasswordRecoveryRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\PasswordRecovery');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function save(PasswordRecovery $passwordRecovery)
        {
            $this->redisRepository->save($passwordRecovery);
            $success = $this->dbRepository->save($passwordRecovery);

            return $success;
        }

        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function delete(PasswordRecovery $passwordRecovery)
        {
            $this->redisRepository->delete($passwordRecovery);
            $success = $this->dbRepository->delete($passwordRecovery);

            return $success;
        }

        /**
         * @param $key
         *
         * @return PasswordRecovery|null
         */
        public function getOneByPasswordRecoveryKey($key)
        {
            $result = $this->redisRepository->getOneByPasswordRecoveryKey($key);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByPasswordRecoveryKey($key);
            }

            return $result;
        }

    }