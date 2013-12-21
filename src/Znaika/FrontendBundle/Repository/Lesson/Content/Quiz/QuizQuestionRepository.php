<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz;

    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion;

    class QuizQuestionRepository extends BaseRepository implements IQuizQuestionRepository
    {
        /**
         * @var IQuizQuestionRepository
         */
        protected $dbRepository;

        /**
         * @var IQuizQuestionRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new QuizQuestionRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Quiz\QuizQuestion');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param QuizQuestion $quizQuestion
         *
         * @return mixed
         */
        public function save(QuizQuestion $quizQuestion)
        {
            $this->redisRepository->save($quizQuestion);
            $success = $this->dbRepository->save($quizQuestion);

            return $success;
        }
    }
