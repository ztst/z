<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Quiz;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer;

    interface IQuizAnswerRepository
    {
        /**
         * @param QuizAnswer $quizAnswer
         *
         * @return mixed
         */
        public function save(QuizAnswer $quizAnswer);

    }