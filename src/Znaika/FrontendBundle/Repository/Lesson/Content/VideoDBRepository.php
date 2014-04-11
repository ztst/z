<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Helper\SphinxClient;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentStatus;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;
    use Znaika\ProfileBundle\Helper\Util\UserRole;

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

        public function getOneByVideoId($videoId)
        {
            return $this->findOneByVideoId($videoId);
        }

        public function getOneByContentDir($dir)
        {
            return $this->findOneByContentDir($dir);
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

        public function getByVideoIds($videoIds)
        {
            if (empty($videoIds))
            {
                return array();
            }

            $queryBuilder = $this->getEntityManager()->createQueryBuilder();

            $queryBuilder->select("v, FIELD(v.videoId, " . implode(", ", $videoIds) . ") as HIDDEN field")
               ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
               ->where($queryBuilder->expr()->in('v.videoId', $videoIds))
               ->orderBy("field");

            return $queryBuilder->getQuery()->getResult();
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

        public function getSupervisorVideosWithQuestions(User $user)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('v')
               ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
               ->innerJoin('v.videoComments', 'vc')
               ->andWhere("vc.isAnswered = :is_answered")
               ->setParameter('is_answered', false)
               ->andWhere("vc.commentType = :comment_type")
               ->setParameter('comment_type', VideoCommentUtil::QUESTION)
               ->addOrderBy('vc.createdTime', 'ASC');

            if ($user->getRole() == UserRole::ROLE_TEACHER)
            {
                $qb->innerJoin('v.supervisors', 's', 'WITH', 's.userId = :user_id')
                   ->setParameter('user_id', $user->getUserId());
            }

            return $qb->getQuery()->getResult();
        }

        public function getVideosWithNotVerifiedComments()
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('v')
               ->from('ZnaikaFrontendBundle:Lesson\Content\Video', 'v')
               ->innerJoin('v.videoComments', 'vc')
               ->andWhere("vc.status = :not_verified")
               ->setParameter('not_verified', VideoCommentStatus::NOT_VERIFIED)
               ->addOrderBy('vc.createdTime', 'ASC');

            return $qb->getQuery()->getResult();
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
