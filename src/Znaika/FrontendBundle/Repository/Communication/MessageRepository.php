<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use FOS\MessageBundle\Model\MessageInterface;
    use FOS\MessageBundle\Model\ParticipantInterface;
    use FOS\MessageBundle\Model\ReadableInterface;
    use FOS\MessageBundle\Model\ThreadInterface;
    use Znaika\FrontendBundle\Entity\Communication\Message;
    use Znaika\FrontendBundle\Entity\Communication\Thread;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class MessageRepository extends BaseRepository implements IMessageRepository
    {
        /**
         * @var IMessageRepository
         */
        protected $dbRepository;

        /**
         * @var IMessageRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new MessageRedisRepository();
            $dbRepository    = $doctrine->getRepository('ZnaikaFrontendBundle:Communication\Message');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * Tells how many unread, non-spam, messages this participant has
         *
         * @param ParticipantInterface $participant
         *
         * @return int the number of unread messages
         */
        public function getNbUnreadMessageByParticipant(ParticipantInterface $participant)
        {
            $result = $this->redisRepository->getNbUnreadMessageByParticipant($participant);
            if (empty($result))
            {
                $result = $this->dbRepository->getNbUnreadMessageByParticipant($participant);
            }

            return $result;
        }

        /**
         * Creates an empty message instance
         *
         * @return MessageInterface
         */
        public function createMessage()
        {
            $result = $this->redisRepository->createMessage();
            if (empty($result))
            {
                $result = $this->dbRepository->createMessage();
            }

            return $result;
        }

        /**
         * Saves a message
         *
         * @param MessageInterface $message
         * @param Boolean $andFlush Whether to flush the changes (default true)
         */
        public function saveMessage(MessageInterface $message, $andFlush = true)
        {
            $this->redisRepository->saveMessage($message, $andFlush);
            $success = $this->dbRepository->saveMessage($message, $andFlush);

            return $success;
        }

        /**
         * Returns the message's fully qualified class MessageManagerInterface.
         *
         * @return string
         */
        public function getClass()
        {
            $result = $this->redisRepository->getClass();
            if (empty($result))
            {
                $result = $this->dbRepository->getClass();
            }

            return $result;
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
            $this->redisRepository->markAsReadByParticipant($readable, $user);
            $success = $this->dbRepository->markAsReadByParticipant($readable, $user);

            return $success;
        }

        /**
         * Marks the readable as unread by this participant
         *
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
            $this->redisRepository->markAsUnreadByParticipant($readable, $user);
            $success = $this->dbRepository->markAsUnreadByParticipant($readable, $user);

            return $success;
        }

        /**
         * @param ThreadInterface $thread
         * @param ParticipantInterface $participant
         * @param boolean $isRead
         */
        public function markIsReadByThreadAndParticipant(ThreadInterface $thread, ParticipantInterface $participant, $isRead)
        {
            foreach ($thread->getMessages() as $message)
            {
                $this->dbRepository->markIsReadByParticipant($message, $participant, $isRead);
            }
        }

        /**
         * @param User $participant
         * @param $messageId
         *
         * @return boolean
         */
        public function markIsDeletedByParticipant(User $participant, $messageId)
        {
            $this->redisRepository->markIsDeletedByParticipant($participant, $messageId);
            $success = $this->dbRepository->markIsDeletedByParticipant($participant, $messageId);

            return $success;
        }

        /**
         * @inheritdoc
         */
        public function countUnreadThreadMessageByParticipant(User $participant, Thread $thread)
        {
            $result = $this->redisRepository->countUnreadThreadMessageByParticipant($participant, $thread);
            if (is_null($result))
            {
                $result = $this->dbRepository->countUnreadThreadMessageByParticipant($participant, $thread);
            }

            return $result;
        }

        /**
         * @inheritdoc
         */
        public function countUnreadThreadsByParticipant(User $participant)
        {
            $result = $this->redisRepository->countUnreadThreadsByParticipant($participant);
            if (is_null($result))
            {
                $result = $this->dbRepository->countUnreadThreadsByParticipant($participant);
            }

            return $result;
        }

        /**
         * @inheritdoc
         */
        public function getThreadMessages(Thread $thread, $offset, $limit)
        {
            $result = $this->redisRepository->getThreadMessages($thread, $offset, $limit);
            if (is_null($result))
            {
                $result = $this->dbRepository->getThreadMessages($thread, $offset, $limit);
            }

            return $result;
        }

        /**
         * @param Thread $thread
         *
         * @return Message
         */
        public function getLastThreadMessage(Thread $thread)
        {
            $result = $this->redisRepository->getLastThreadMessage($thread);
            if (is_null($result))
            {
                $result = $this->dbRepository->getLastThreadMessage($thread);
            }

            return $result;
        }
    }