<?
    namespace Znaika\FrontendBundle\Entity\Communication;

    use Doctrine\ORM\Mapping as ORM;
    use FOS\MessageBundle\Entity\Message as BaseMessage;
    use FOS\MessageBundle\Model\ParticipantInterface;

    class Message extends BaseMessage
    {
        /**
         * @var integer
         */
        protected $messageId;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $metadata;

        /**
         * @var \Znaika\FrontendBundle\Entity\Communication\Thread
         */
        protected $thread;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        protected $sender;

        public function getId()
        {
            return $this->messageId;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return MessageMetadata
         */
        public function getMetadataForParticipant(ParticipantInterface $participant)
        {
            return parent::getMetadataForParticipant($participant);
        }
    }
