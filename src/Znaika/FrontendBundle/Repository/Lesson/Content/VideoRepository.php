<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

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
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Video');

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
            if ( empty($result) )
            {
                $result = $this->dbRepository->getOneByUrlName($name);
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
            if ( empty($result) )
            {
                $result = $this->dbRepository->getVideosForCatalog($classNumber, $subjectName);
            }
            return $result;
        }

        public function getVideosBySearchString($searchString, $limit = null)
        {
            $result = $this->redisRepository->getVideosBySearchString($searchString, $limit);
            if ( empty($result) )
            {
                $result = $this->dbRepository->getVideosBySearchString($searchString, $limit);
            }
            return $result;
        }

        public function getNotSimilarVideosBySearchString(Video $video, $searchString, $limit = null)
        {
            $result = $this->redisRepository->getNotSimilarVideosBySearchString($video, $searchString, $limit);
            if ( empty($result) )
            {
                $result = $this->dbRepository->getNotSimilarVideosBySearchString($video, $searchString, $limit);
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
            if ( empty($result) )
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
            if ( empty($result) )
            {
                $result = $this->dbRepository->getPopularVideo($limit);
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

    }
