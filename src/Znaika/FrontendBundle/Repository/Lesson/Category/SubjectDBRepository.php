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
         * @param $grade
         *
         * @return array|null
         */
        public function getByGrade($grade)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('s')
                         ->from('ZnaikaFrontendBundle:Lesson\Category\Subject', 's')
                         ->innerJoin('s.chapters', 'ch')
                         ->andWhere('ch.grade = :grade')
                         ->setParameter('grade', $grade)
                         ->addGroupBy('s')
                         ->addOrderBy('s.name', 'ASC');

            $subjects = $queryBuilder->getQuery()->getResult();

            return $subjects;
        }

        /**
         * @return array|null
         */
        public function getAll()
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('s')
                         ->from('ZnaikaFrontendBundle:Lesson\Category\Subject', 's')
                         ->addOrderBy('s.createdTime', 'ASC');

            $subjects = $queryBuilder->getQuery()->getResult();

            return $subjects;
        }

        public function getNotEmptySubjects()
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('s.subjectId, s.name, s.urlName, v.grade')
                         ->from('ZnaikaFrontendBundle:Lesson\Category\Subject', 's')
                         ->innerJoin('s.videos', 'v')
                         ->addGroupBy('v.grade')
                         ->addGroupBy('v.subject')
                         ->addOrderBy('s.name, v.grade', 'ASC');

            $subjects = $queryBuilder->getQuery()->getArrayResult();

            return $subjects;
        }
    }
