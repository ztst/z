<?

    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;

    interface IChapterRepository
    {
        /**
         * @return array|null
         */
        public function getAll();

        /**
         * @param $chapterId
         *
         * @return Chapter|null
         */
        public function getOneById($chapterId);
    }
