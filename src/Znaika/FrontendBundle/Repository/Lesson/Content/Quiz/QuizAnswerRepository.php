<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz;

    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer;

    class QuizAnswerRepository extends BaseRepository implements IQuizAnswerRepository
    {
        /**
         * @var IQuizAnswerRepository
         */
        protected $dbRepository;

        /**
         * @var IQuizAnswerRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new QuizAnswerRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Quiz\QuizAnswer');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param QuizAnswer $quizAnswer
         *
         * @return mixed
         */
        public function save(QuizAnswer $quizAnswer)
        {
            $this->redisRepository->save($quizAnswer);
            $success = $this->dbRepository->save($quizAnswer);

            return $success;
        }
    }
