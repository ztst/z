<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;

    class VideoAttachmentRedisRepository implements IVideoAttachmentRepository
    {
        /**
         * @param VideoAttachment $videoAttachment
         *
         * @return mixed
         */
        public function save(VideoAttachment $videoAttachment)
        {
            return true;
        }

        /**
         * @param integer $videoAttachmentId
         *
         * @return VideoAttachment
         */
        public function getOneByVideoAttachmentId($videoAttachmentId)
        {
            return null;
        }
    }