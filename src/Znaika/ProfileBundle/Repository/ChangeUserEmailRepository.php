<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\ChangeUserEmail;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class ChangeUserEmailRepository extends BaseRepository implements IChangeUserEmailRepository
    {
        /**
         * @var IChangeUserEmailRepository
         */
        protected $redisRepository;

        /**
         * @var IChangeUserEmailRepository
         */
        protected $dbRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new ChangeUserEmailRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaProfileBundle:ChangeUserEmail');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function save(ChangeUserEmail $changeUserEmail)
        {
            $this->redisRepository->save($changeUserEmail);
            $success = $this->dbRepository->save($changeUserEmail);

            return $success;
        }

        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function delete(ChangeUserEmail $changeUserEmail)
        {
            $this->redisRepository->delete($changeUserEmail);
            $success = $this->dbRepository->delete($changeUserEmail);

            return $success;
        }

        /**
         * @param $key
         *
         * @return ChangeUserEmail
         */
        public function getOneByChangeKey($key)
        {
            $result = $this->redisRepository->getOneByChangeKey($key);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByChangeKey($key);
            }

            return $result;
        }
    }