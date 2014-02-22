<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class VideoCommentRepository extends BaseRepository implements IVideoCommentRepository
    {
        /**
         * @var IVideoCommentRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoCommentRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new VideoCommentRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\VideoComment');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param VideoComment $videoComment
         *
         * @return mixed
         */
        public function save(VideoComment $videoComment)
        {
            $this->redisRepository->save($videoComment);
            $success = $this->dbRepository->save($videoComment);

            return $success;
        }

        /**
         * @param Video $video
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getLastVideoComments(Video $video, $limit)
        {
            $result = $this->redisRepository->getLastVideoComments($video, $limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getLastVideoComments($video, $limit);
            }

            return $result;
        }

        /**
         * @param Video $video
         * @param $offset
         * @param $limit
         *
         * @return VideoComment[]
         */
        public function getVideoComments($video, $offset, $limit)
        {
            $result = $this->redisRepository->getVideoComments($video, $offset, $limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getVideoComments($video, $offset, $limit);
            }

            return $result;
        }

    }