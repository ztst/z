<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class UserAttemptRepository extends BaseRepository implements IUserAttemptRepository
    {
        /**
         * @var IUserAttemptRepository
         */
        protected $dbRepository;

        /**
         * @var IUserAttemptRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserAttemptRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Quiz\Attempt\UserAttempt');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param UserAttempt $userAttempt
         *
         * @return mixed
         */
        public function save(UserAttempt $userAttempt)
        {
            $this->redisRepository->save($userAttempt);
            $success = $this->dbRepository->save($userAttempt);

            return $success;
        }

    }
