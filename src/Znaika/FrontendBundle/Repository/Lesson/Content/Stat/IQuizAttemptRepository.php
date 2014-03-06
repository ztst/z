<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Stat;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Stat\QuizAttempt;

    interface IQuizAttemptRepository
    {
        public function save(QuizAttempt $quizAttempt);

        public function getUserQuizAttempt($userId, $quizId);
    }
