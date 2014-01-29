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

        public function getVideosBySearchString($searchString, $limit = null)
        {
            $searchString = "%{$searchString}%";

            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->where($queryBuilder->expr()->like('v.name', $queryBuilder->expr()->literal($searchString)))
                         ->addOrderBy('v.createdTime', 'DESC');

            if (!is_null($limit))
            {
                $queryBuilder->setMaxResults($limit);
            }

            $videos = $queryBuilder->getQuery()->getResult();

            return $videos;
        }

        public function getNotSimilarVideosBySearchString(Video $video, $searchString, $limit = null)
        {
            $videoIds      = array($video->getVideoId());
            $similarVideos = $video->getSimilarVideos();
            foreach ($similarVideos as $similarVideo)
            {
                array_push($videoIds, $similarVideo->getVideoId());
            }

            $searchString = "%{$searchString}%";

            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->where($queryBuilder->expr()->like('v.name', $queryBuilder->expr()->literal($searchString)))
                         ->andWhere('v.videoId NOT IN (:video_ids)')
                         ->setParameter('video_ids', $videoIds)
                         ->addOrderBy('v.name');

            if (!is_null($limit))
            {
                $queryBuilder->setMaxResults($limit);
            }

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
