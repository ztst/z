<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class VideoAttachmentRepository extends BaseRepository implements IVideoAttachmentRepository
    {
        /**
         * @var IVideoAttachmentRepository
         */
        protected $dbRepository;

        /**
         * @var IVideoAttachmentRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new VideoAttachmentRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Lesson\Content\Attachment\VideoAttachment');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param VideoAttachment $videoAttachment
         *
         * @return boolean
         */
        public function save(VideoAttachment $videoAttachment)
        {
            $this->redisRepository->save($videoAttachment);
            $result = $this->dbRepository->save($videoAttachment);

            return $result;
        }

        /**
         * @param integer $videoAttachmentId
         *
         * @return VideoAttachment
         */
        public function getOneByVideoAttachmentId($videoAttachmentId)
        {
            $result = $this->redisRepository->getOneByVideoAttachmentId($videoAttachmentId);
            if (is_null($result))
            {
                $result = $this->dbRepository->getOneByVideoAttachmentId($videoAttachmentId);
            }

            return $result;
        }
    }