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
                         ->innerJoin('s.videos', 'v')
                         ->andWhere('v.grade = :grade')
                         ->setParameter('grade', $grade)
                         ->addGroupBy('s')
                         ->addOrderBy('v.createdTime', 'DESC');

            $subjects = $queryBuilder->getQuery()->getResult();

            return $subjects;
        }

        /**
         * @return array|null
         */
        public function getAll()
        {
            return $this->findAll();
        }

        /**
         * @param Subject $subject
         *
         * @return array|null
         */
        public function getSubjectClasses(Subject $subject)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v.grade')
                         ->from('ZnaikaFrontendBundle:Lesson\Category\Subject', 's')
                         ->innerJoin('s.videos', 'v')
                         ->andWhere('v.subject = :subject')
                         ->setParameter('subject', $subject)
                         ->addGroupBy('v.grade')
                         ->addOrderBy('v.createdTime', 'DESC');

            $result = array();
            $grades = $queryBuilder->getQuery()->getResult();
            foreach ( $grades as $grade )
            {
                array_push($result, $grade['grade']);
            }

            return $result;
        }
    }
