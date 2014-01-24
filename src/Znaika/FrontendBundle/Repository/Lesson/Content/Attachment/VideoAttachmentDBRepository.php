<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content\Attachment;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;

    class VideoAttachmentDBRepository extends EntityRepository implements IVideoAttachmentRepository
    {
        /**
         * @param VideoAttachment $videoAttachment
         *
         * @return bool
         */
        public function save(VideoAttachment $videoAttachment)
        {
            $this->getEntityManager()->persist($videoAttachment);
            $this->getEntityManager()->flush();
        }

        /**
         * @param integer $videoAttachmentId
         *
         * @return VideoAttachment
         */
        public function getOneByVideoAttachmentId($videoAttachmentId)
        {
            return $this->find($videoAttachmentId);
        }
    }