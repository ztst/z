<?

    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;

    interface IChapterRepository
    {
        /**
         * @return Chapter[]
         */
        public function getAll();

        /**
         * @param $chapterId
         *
         * @return Chapter
         */
        public function getOneById($chapterId);

        /**
         * @param int $grade
         * @param int $subjectId
         *
         * @return Chapter[]
         */
        public function getChaptersForCatalog($grade, $subjectId);

        /**
         * @param $name
         * @param $grade
         * @param $subjectId
         *
         * @return Chapter
         */
        public function getOne($name, $grade, $subjectId);
    }
