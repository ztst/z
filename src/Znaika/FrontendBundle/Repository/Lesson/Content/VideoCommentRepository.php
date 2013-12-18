<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

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

    }