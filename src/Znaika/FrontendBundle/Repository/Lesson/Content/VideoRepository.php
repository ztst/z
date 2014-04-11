<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    class VideoRepository extends BaseRepository implements IVideoRepository
    {
        /**
         * @var IVideoRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new VideoRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Video');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param $name
         *
         * @return Video|null
         */
        public function getOneByUrlName($name)
        {
            $result = $this->redisRepository->getOneByUrlName($name);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByUrlName($name);
            }

            return $result;
        }

        public function getOneByContentDir($dir)
        {
            $result = $this->redisRepository->getOneByContentDir($dir);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByContentDir($dir);
            }

            return $result;
        }

        /**
         * @param null $classNumber
         * @param null $subjectName
         *
         * @return array|null
         */
        public function getVideosForCatalog($classNumber = null, $subjectName = null)
        {
            $result = $this->redisRepository->getVideosForCatalog($classNumber, $subjectName);
            if (empty($result))
            {
                $result = $this->dbRepository->getVideosForCatalog($classNumber, $subjectName);
            }

            return $result;
        }

        public function getByVideoIds($videoIds)
        {
            $result = $this->redisRepository->getByVideoIds($videoIds);
            if (empty($result))
            {
                $result = $this->dbRepository->getByVideoIds($videoIds);
            }

            return $result;
        }

        /**
         * @param $limit
         *
         * @return Video[]
         */
        public function getNewestVideo($limit)
        {
            $result = $this->redisRepository->getNewestVideo($limit);
            if (empty($result))
            {
                $result = $this->dbRepository->getNewestVideo($limit);
            }

            return $result;
        }

        /**
         * @param $limit
         *
         * @return Video[]
         */
        public function getPopularVideo($limit)
        {
            $result = $this->redisRepository->getPopularVideo($limit);
            if (empty($result))
            {
                $result = $this->dbRepository->getPopularVideo($limit);
            }

            return $result;
        }

        public function getVideoByChapter($chapter)
        {
            $result = $this->redisRepository->getVideoByChapter($chapter);
            if (empty($result))
            {
                $result = $this->dbRepository->getVideoByChapter($chapter);
            }

            return $result;
        }

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function save(Video $video)
        {
            $this->redisRepository->save($video);
            $success = $this->dbRepository->save($video);

            return $success;
        }

        public function getOneByVideoId($videoId)
        {
            $result = $this->redisRepository->getOneByVideoId($videoId);
            if (is_null($result))
            {
                $result = $this->dbRepository->getOneByVideoId($videoId);
            }

            return $result;
        }

        public function moveVideo(Video $video, $direction)
        {
            $this->redisRepository->moveVideo($video, $direction);
            $success = $this->dbRepository->moveVideo($video, $direction);

            return $success;
        }

        /**
         * @param Chapter $chapter
         *
         * @return int
         */
        public function getMaxChapterOrderPriority(Chapter $chapter)
        {
            $result = $this->redisRepository->getMaxChapterOrderPriority($chapter);
            if (is_null($result))
            {
                $result = $this->dbRepository->getMaxChapterOrderPriority($chapter);
            }

            return $result;
        }

        public function getSupervisorVideosWithQuestions(User $user)
        {
            $result = $this->redisRepository->getSupervisorVideosWithQuestions($user);
            if (is_null($result))
            {
                $result = $this->dbRepository->getSupervisorVideosWithQuestions($user);
            }

            return $result;
        }

        public function getVideosWithNotVerifiedComments()
        {
            $result = $this->redisRepository->getVideosWithNotVerifiedComments();
            if (is_null($result))
            {
                $result = $this->dbRepository->getVideosWithNotVerifiedComments();
            }

            return $result;
        }
    }
