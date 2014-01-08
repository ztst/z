<?

    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;

    interface ISubjectRepository
    {
        /**
         * @return array|null
         */
        public function getAll();

        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name);

        /**
         * @param $grade
         *
         * @return array|null
         */
        public function getByGrade($grade);

        /**
         * @param Subject $subject
         *
         * @return array|null
         */
        public function getSubjectClasses(Subject $subject);
    }
