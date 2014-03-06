<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;

    interface IQuizRepository
    {
        /**
         * @param Quiz $quiz
         *
         * @return boolean
         */
        public function save(Quiz $quiz);

        /**
         * @param $videoId
         *
         * @return Quiz
         */
        public function getOneByVideoId($videoId);

        /**
         * @param $name
         *
         * @return Quiz
         */
        public function getOneByName($name);
    }
