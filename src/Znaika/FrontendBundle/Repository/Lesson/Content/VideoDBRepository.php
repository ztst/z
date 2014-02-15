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

        public function getVideoByChapter($chapter)
        {
            return $this->findByChapter($chapter);
        }

        public function getVideosBySearchString($searchString, $limit = null, $page = null)
        {
            $queryBuilder = $this->prepareSearchQuery($searchString);
            $queryBuilder->select('v');

            if (!is_null($limit))
            {
                $queryBuilder->setMaxResults($limit);
                if (!is_null($page))
                {
                    $queryBuilder->setFirstResult($limit * $page);
                }
            }

            $videos = $queryBuilder->getQuery()->getResult();

            return $videos;
        }

        public function countVideosBySearchString($searchString)
        {
            $queryBuilder = $this->prepareSearchQuery($searchString);
            $queryBuilder->select('count(v)');

            return intval($queryBuilder->getQuery()->getSingleScalarResult());
        }

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video)
        {
            $this->getEntityManager()->persist($video);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $limit
         *
         * @return Video[]
         */
        public function getNewestVideo($limit)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->addOrderBy('v.createdTime', 'DESC')
                         ->setMaxResults($limit);

            $videos = $queryBuilder->getQuery()->getResult();

            return $videos;
        }

        /**
         * @param $limit
         *
         * @return Video[]
         */
        public function getPopularVideo($limit)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->addOrderBy('v.views', 'DESC')
                         ->setMaxResults($limit);

            $videos = $queryBuilder->getQuery()->getResult();

            return $videos;
        }

        /**
         * @param $searchString
         *
         * @return \Doctrine\ORM\QueryBuilder
         */
        private function prepareSearchQuery($searchString)
        {
            $searchString = "%{$searchString}%";

            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->where($queryBuilder->expr()->like('v.name', $queryBuilder->expr()->literal($searchString)))
                         ->addOrderBy('v.grade, v.chapter', 'ASC');

            return $queryBuilder;
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
