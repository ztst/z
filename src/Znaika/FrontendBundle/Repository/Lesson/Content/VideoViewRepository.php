<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoView;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class VideoViewRepository extends BaseRepository implements IVideoViewRepository
    {
        /**
         * @var IVideoViewRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoViewRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new VideoViewRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\VideoView');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param VideoView $videoView
         *
         * @return mixed
         */
        public function save(VideoView $videoView)
        {
            $this->redisRepository->save($videoView);
            $success = $this->dbRepository->save($videoView);

            return $success;
        }

        /**
         * @param $video
         * @param $user
         *
         * @return VideoView
         */
        public function getOneByVideoAndUser($video, $user)
        {
            $result = $this->redisRepository->getOneByVideoAndUser($video, $user);
            if (empty($result))
            {
                $result = $this->dbRepository->getOneByVideoAndUser($video, $user);
            }

            return $result;
        }
    }