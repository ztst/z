<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz\Attempt;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class IUserAttemptRepository extends BaseRepository implements IUserQuestionAnswerRepository
    {
        /**
         * @var IUserQuestionAnswerRepository
         */
        protected $dbRepository;

        /**
         * @var IUserQuestionAnswerRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new UserQuestionAnswerRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Quiz\Attempt\UserQuestionAnswer');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param UserQuestionAnswer $userQuestionAnswer
         *
         * @return mixed
         */
        public function save(UserQuestionAnswer $userQuestionAnswer)
        {
            $this->redisRepository->save($userQuestionAnswer);
            $success = $this->dbRepository->save($userQuestionAnswer);

            return $success;
        }
    }
