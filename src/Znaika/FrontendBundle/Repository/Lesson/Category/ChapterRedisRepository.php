<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;

    class ChapterRedisRepository implements IChapterRepository
    {
        /**
         * @return array|null
         */
        public function getAll()
        {
            return null;
        }

        /**
         * @param $chapterId
         *
         * @return null|Chapter
         */
        public function getOneById($chapterId)
        {
            return null;
        }

    }
