<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;

    class QuizRedisRepository implements IQuizRepository
    {
        /**
         * @param Quiz $quiz
         *
         * @return boolean
         */
        public function save(Quiz $quiz)
        {
            return true;
        }

        /**
         * @param $videoId
         *
         * @return Quiz
         */
        public function getOneByVideoId($videoId)
        {
            return null;
        }
    }