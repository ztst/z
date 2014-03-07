<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;

    class QuizRedisRepository implements IQuizRepository
    {
        public function save(Quiz $quiz)
        {
            return true;
        }

        public function getOneByVideoId($videoId)
        {
            return null;
        }
    }