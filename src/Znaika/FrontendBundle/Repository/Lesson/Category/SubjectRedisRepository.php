<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;

    class SubjectRedisRepository implements ISubjectRepository
    {
        /**
         * @return array|null
         */
        public function getAll()
        {
            return null;
        }

        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name)
        {
            return null;
        }

        /**
         * @param $grade
         *
         * @return array|null
         */
        public function getByGrade($grade)
        {
            return null;
        }

        /**
         * @param Subject $subject
         *
         * @return array|null
         */
        public function getSubjectClasses(Subject $subject)
        {
            return null;
        }
    }
