<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
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
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->andWhere('v.chapter = :chapter_id')
                         ->setParameter('chapter_id', $chapter)
                         ->addOrderBy('v.orderPriority', 'ASC');

            return $queryBuilder->getQuery()->getResult();
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
         * @param Chapter $chapter
         *
         * @return int
         */
        public function getMaxChapterOrderPriority(Chapter $chapter)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('max(v.orderPriority)')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->andWhere('v.chapter = :chapter_id')
                         ->setParameter('chapter_id', $chapter->getChapterId());

            return intval($queryBuilder->getQuery()->getSingleScalarResult());
        }

        public function moveVideo(Video $video, $direction)
        {
            $isMoved = false;
            if ($direction == "up")
            {
                $prevVideo = $this->getPrevVideo($video);
                if ($prevVideo)
                {
                    $currentOrder = $video->getOrderPriority();
                    $video->setOrderPriority($currentOrder - 1);
                    $prevVideo->setOrderPriority($currentOrder);

                    $this->save($video);
                    $this->save($prevVideo);
                    $isMoved = true;
                }
            }
            else
            {
                $nextVideo = $this->getNextVideo($video);
                if ($nextVideo)
                {
                    $currentOrder = $video->getOrderPriority();
                    $video->setOrderPriority($currentOrder + 1);
                    $nextVideo->setOrderPriority($currentOrder);

                    $this->save($video);
                    $this->save($nextVideo);
                    $isMoved = true;
                }
            }
            return $isMoved;
        }

        /**
         * @param Video $video
         *
         * @return Video
         */
        private function getNextVideo(Video $video)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->andWhere('v.chapter = :chapter_id')
                         ->setParameter('chapter_id', $video->getChapter()->getChapterId())
                         ->andWhere('v.orderPriority = :order_priority')
                         ->setParameter('order_priority', $video->getOrderPriority() + 1);

            return $queryBuilder->getQuery()->getOneOrNullResult();
        }

        /**
         * @param Video $video
         *
         * @return Video
         */
        private function getPrevVideo(Video $video)
        {
            $queryBuilder = $this->getEntityManager()
                                 ->createQueryBuilder();
            $queryBuilder->select('v')
                         ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
                         ->andWhere('v.chapter = :chapter_id')
                         ->setParameter('chapter_id', $video->getChapter()->getChapterId())
                         ->andWhere('v.orderPriority = :order_priority')
                         ->setParameter('order_priority', $video->getOrderPriority() - 1);

            return $queryBuilder->getQuery()->getOneOrNullResult();
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
