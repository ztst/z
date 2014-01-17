<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use FOS\MessageBundle\Model\MessageInterface;
    use FOS\MessageBundle\Model\ParticipantInterface;
    use FOS\MessageBundle\Model\ReadableInterface;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class MessageRedisRepository implements IMessageRepository
    {
        /**
         * Tells how many unread, non-spam, messages this participant has
         *
         * @param ParticipantInterface $participant
         *
         * @return int the number of unread messages
         */
        public function getNbUnreadMessageByParticipant(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * Creates an empty message instance
         *
         * @return MessageInterface
         */
        public function createMessage()
        {
            return null;
        }

        /**
         * Saves a message
         *
         * @param MessageInterface $message
         * @param Boolean $andFlush Whether to flush the changes (default true)
         */
        public function saveMessage(MessageInterface $message, $andFlush = true)
        {
        }

        /**
         * Returns the message's fully qualified class MessageManagerInterface.
         *
         * @return string
         */
        public function getClass()
        {
            return null;
        }

        /**
         * Marks the readable as read by this participant
         * Must be applied directly to the storage,
         * without modifying the readable state.
         * We want to show the unread readables on the page,
         * as well as marking them as read.
         *
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
        }

        /**
         * Marks the readable as unread by this participant
         *
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
        }

        /**
         * @param User $participant
         * @param $messageId
         *
         * @return boolean
         */
        public function markIsDeletedByParticipant(User $participant, $messageId)
        {
            return true;
        }
    }