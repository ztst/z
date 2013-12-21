<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion;

    class QuizQuestionRedisRepository implements IQuizQuestionRepository
    {
        /**
         * @param QuizQuestion $quizQuestion
         *
         * @return mixed
         */
        public function save(QuizQuestion $quizQuestion)
        {
            return true;
        }
    }
