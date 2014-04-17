<?
    namespace Znaika\FrontendBundle\Repository\Communication;


    use FOS\MessageBundle\ModelManager\MessageManagerInterface;
    use Znaika\FrontendBundle\Entity\Communication\Message;
    use Znaika\FrontendBundle\Entity\Communication\Thread;
    use Znaika\ProfileBundle\Entity\User;

    interface IMessageRepository extends MessageManagerInterface
    {
        /**
         * @param User $participant
         * @param $messageId
         *
         * @return boolean
         */
        public function markIsDeletedByParticipant(User $participant, $messageId);

        /**
         * @param User $participant
         * @param Thread $thread
         *
         * @return int
         */
        public function countUnreadThreadMessageByParticipant(User $participant, Thread $thread);

        /**
         * @param User $participant
         *
         * @return int
         */
        public function countUnreadThreadsByParticipant(User $participant);

        /**
         * @param Thread $thread
         * @param $offset
         * @param $limit
         *
         * @return Message[]
         */
        public function getThreadMessages(Thread $thread, $offset, $limit);

        /**
         * @param Thread $thread
         *
         * @return Message
         */
        public function getLastThreadMessage(Thread $thread);
    }
