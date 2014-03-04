<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Stat;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Stat\QuizAttempt;
    use Znaika\FrontendBundle\Repository\Lesson\Content\Stat\IQuizAttemptRepository;

    class QuizAttemptRedisRepository implements IQuizAttemptRepository
    {
        public function save(QuizAttempt $quizAttempt)
        {
            return true;
        }

        public function getUserQuizAttempt($userId, $quizId)
        {
            return null;
        }
    }