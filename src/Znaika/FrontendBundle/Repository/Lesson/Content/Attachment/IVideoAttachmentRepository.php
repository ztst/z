<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;

    interface IVideoAttachmentRepository
    {
        /**
         * @param VideoAttachment $videoAttachment
         *
         * @return boolean
         */
        public function save(VideoAttachment $videoAttachment);

        /**
         * @param integer $videoAttachmentId
         *
         * @return VideoAttachment
         */
        public function getOneByVideoAttachmentId($videoAttachmentId);
    }
