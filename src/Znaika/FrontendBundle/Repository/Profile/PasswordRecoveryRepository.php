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
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Profile\PasswordRecovery');

            $this->setDBRepository($dbRepository);
        }

        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function save(PasswordRecovery $passwordRecovery)
        {
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
            $result = $this->dbRepository->getOneByPasswordRecoveryKey($key);

            return $result;
        }

    }