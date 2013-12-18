<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Category;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Subject;

    class SubjectDBRepository extends EntityRepository implements ISubjectRepository
    {
        /**
         * @param $name
         *
         * @return Subject|null
         */
        public function getOneByUrlName($name)
        {
            return $this->findOneByUrlName($name);
        }

        /**
         * @return array|null
         */
        public function getAll()
        {
            return $this->findAll();
        }
    }
