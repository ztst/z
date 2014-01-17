<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    class VideoDBRepository extends EntityRepository implements IVideoRepository
    {
        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return array|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->addOrderBy('v.createdTime', 'DESC');
            //->setMaxResults(3) TODO: set limit;

            $this->prepareSubjectNameFilter($subjectName, $queryBuilder);
            $this->prepareClassNumberFilter($classNumber, $queryBuilder);

            $videos = $queryBuilder->getQuery()->getResult();

            return $videos;
        }

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name)
        {
            return $this->findOneByUrlName($name);
        }

        /**
         * @param string $searchString
         *
         * @return array|null
         */
        public function getVideosBySearchString($searchString)
        {
            $searchString = "%{$searchString}%";

            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->where($queryBuilder->expr()->like('v.name', $queryBuilder->expr()->literal($searchString)))
                         ->addOrderBy('v.createdTime', 'DESC');

            $videos = $queryBuilder->getQuery()->getResult();

            return $videos;
        }

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video)
        {
            $this->_em->persist($video);
            $this->_em->flush();
        }

        /**
         * @param $classNumber
         * @param $queryBuilder
         */
        private function prepareClassNumberFilter($classNumber, $queryBuilder)
        {
            if ($classNumber)
            {
                $queryBuilder->andWhere('v.grade = :classNumber')
                             ->setParameter('classNumber', $classNumber);
            }
        }

        private function prepareSubjectNameFilter($subjectName, $queryBuilder)
        {
            if ($subjectName)
            {
                $queryBuilder->innerJoin('v.subject', 's')
                             ->andWhere('s.urlName = :subjectName')
                             ->setParameter('subjectName', $subjectName);
            }
        }
    }
