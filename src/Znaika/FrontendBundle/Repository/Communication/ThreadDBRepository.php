<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use FOS\MessageBundle\Model\ParticipantInterface;
    use FOS\MessageBundle\Model\ReadableInterface;
    use FOS\MessageBundle\Model\ThreadInterface;
    use Doctrine\ORM\Query\Builder;
    use Znaika\FrontendBundle\Entity\Communication\Thread;
    use Znaika\FrontendBundle\Entity\Communication\ThreadMetadata;
    use Znaika\ProfileBundle\Entity\User;

    class ThreadDBRepository extends EntityRepository implements IThreadRepository
    {
        /**
         * @var MessageRepository
         */
        protected $messageManager;

        public function __construct($em, ClassMetadata $class)
        {
            parent::__construct($em, $class);

            $this->messageManager = new MessageRepository($em);
        }


        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $participant
         */
        public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $participant)
        {
            $this->messageManager->markIsReadByThreadAndParticipant($readable, $participant, true);
        }

        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $participant
         */
        public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $participant)
        {
            $this->messageManager->markIsReadByThreadAndParticipant($readable, $participant, false);
        }

        /**
         * @param $id
         *
         * @return ThreadInterface|null
         */
        public function findThreadById($id)
        {
            return $this->find($id);
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return Builde
         */
        public function getParticipantInboxThreadsQueryBuilder(ParticipantInterface $participant)
        {
            return $this->createQueryBuilder('t')
                        ->innerJoin('t.metadata', 'tm')
                        ->innerJoin('tm.participant', 'p')

                // the participant is in the thread participants
                        ->andWhere('p.userId = :user_id')
                        ->setParameter('user_id', $participant->getId())

                // the thread does not contain spam or flood
                        ->andWhere('t.isSpam = :isSpam')
                        ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

                // the thread is not deleted by this participant
                        ->andWhere('tm.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

                // there is at least one message written by an other participant
                        ->andWhere('tm.lastMessageDate IS NOT NULL')

                // sort by date of last message written by an other participant
                        ->orderBy('tm.lastMessageDate', 'DESC');
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findParticipantInboxThreads(ParticipantInterface $participant)
        {
            return $this->getParticipantInboxThreadsQueryBuilder($participant)
                        ->getQuery()
                        ->execute();
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return Builder a query builder suitable for pagination
         */
        public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant)
        {
            return $this->createQueryBuilder('t')
                        ->innerJoin('t.metadata', 'tm')
                        ->innerJoin('tm.participant', 'p')

                // the participant is in the thread participants
                        ->andWhere('p.userId = :user_id')
                        ->setParameter('user_id', $participant->getId())

                // the thread does not contain spam or flood
                        ->andWhere('t.isSpam = :isSpam')
                        ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

                // the thread is not deleted by this participant
                        ->andWhere('tm.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

                // there is at least one message written by this participant
                        ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')

                // sort by date of last message written by this participant
                        ->orderBy('tm.lastParticipantMessageDate', 'DESC');
        }

        /**
         * Finds not deleted threads from a participant,
         * containing at least one message written by this participant,
         * ordered by last message written by this participant in reverse order.
         * In one word: an sentbox.
         *
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findParticipantSentThreads(ParticipantInterface $participant)
        {
            return $this->getParticipantSentThreadsQueryBuilder($participant)
                        ->getQuery()
                        ->execute();
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return Builder a query builder suitable for pagination
         */
        public function getParticipantDeletedThreadsQueryBuilder(ParticipantInterface $participant)
        {
            return $this->createQueryBuilder('t')
                        ->innerJoin('t.metadata', 'tm')
                        ->innerJoin('tm.participant', 'p')

                // the participant is in the thread participants
                        ->andWhere('p.userId = :user_id')
                        ->setParameter('user_id', $participant->getId())

                // the thread is deleted by this participant
                        ->andWhere('tm.isDeleted = :isDeleted')
                        ->setParameter('isDeleted', true, \PDO::PARAM_BOOL)

                // sort by date of last message
                        ->orderBy('tm.lastMessageDate', 'DESC');
        }

        /**
         * Finds deleted threads from a participant,
         * ordered by last message date
         *
         * @param ParticipantInterface $participant
         *
         * @return ThreadInterface[]
         */
        public function findParticipantDeletedThreads(ParticipantInterface $participant)
        {
            return $this->getParticipantDeletedThreadsQueryBuilder($participant)
                        ->getQuery()
                        ->execute();
        }

        /**
         * @param ParticipantInterface $participant
         * @param string $search
         *
         * @return \FOS\MessageBundle\ModelManager\Builder|void
         * @throws \Exception
         */
        public function getParticipantThreadsBySearchQueryBuilder(ParticipantInterface $participant, $search)
        {
            throw new \Exception('not yet implemented');
        }

        /**
         * Finds not deleted threads for a participant,
         * matching the given search term
         * ordered by last message not written by this participant in reverse order.
         *
         * @param ParticipantInterface $participant
         * @param string $search
         *
         * @return array of ThreadInterface
         */
        public function findParticipantThreadsBySearch(ParticipantInterface $participant, $search)
        {
            return $this->getParticipantThreadsBySearchQueryBuilder($participant, $search)
                        ->getQuery()
                        ->execute();
        }

        /**
         * Gets threads created by a participant
         *
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findThreadsCreatedBy(ParticipantInterface $participant)
        {
            return $this->createQueryBuilder('t')
                        ->innerJoin('t.createdBy', 'p')

                        ->where('p.userId = :participant_id')
                        ->setParameter('participant_id', $participant->getId())

                        ->getQuery()
                        ->execute();
        }

        /**
         * @return ThreadInterface
         */
        public function createThread()
        {
            return new Thread();
        }

        /**
         * Saves a thread
         *
         * @param ThreadInterface $thread
         * @param Boolean $andFlush Whether to flush the changes (default true)
         */
        public function saveThread(ThreadInterface $thread, $andFlush = true)
        {
            $this->denormalize($thread);
            $this->getEntityManager()->persist($thread);
            if ($andFlush)
            {
                $this->getEntityManager()->flush();
            }
        }

        /**
         * Deletes a thread
         * This is not participant deletion but real deletion
         *
         * @param ThreadInterface $thread the thread to delete
         */
        public function deleteThread(ThreadInterface $thread)
        {
            $this->getEntityManager()->remove($thread);
            $this->getEntityManager()->flush();
        }

        /**
         * @param User $participant
         *
         * @return array of ThreadInterface
         */
        public function findParticipantAllThreads(User $participant)
        {
            $builder = $this->createQueryBuilder('t')
                            ->innerJoin('t.metadata', 'tm')
                            ->innerJoin('tm.participant', 'p')

                // the participant is in the thread participants
                            ->andWhere('p.userId = :user_id')
                            ->setParameter('user_id', $participant->getId())

                // the thread does not contain spam or flood
                            ->andWhere('t.isSpam = :isSpam')
                            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

                // the thread is not deleted by this participant
                            ->andWhere('tm.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

                // sort by date of last message written by this participant
                            ->orderBy('tm.lastParticipantMessageDate', 'DESC');

            return $builder->getQuery()->execute();
        }

        /**
         * @param User $firstUser
         * @param User $secondUser
         *
         * @return ThreadInterface
         */
        public function findThreadByUsers(User $firstUser, User $secondUser)
        {
            $builder = $this->createQueryBuilder('t')
                            ->innerJoin('t.metadata', 'tm')
                            ->innerJoin('tm.participant', 'p')

                // the participant is in the thread participants
                            ->andWhere('(p.userId = :user_id AND t.createdBy = :second_user_id) OR (p.userId = :second_user_id AND t.createdBy = :user_id)')
                            ->setParameter('user_id', $firstUser->getId())
                            ->setParameter('second_user_id', $secondUser->getId())

                // the thread does not contain spam or flood
                            ->andWhere('t.isSpam = :isSpam')
                            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

                // the thread is not deleted by this participant
                            ->andWhere('tm.isDeleted = :isDeleted')
                            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

                // sort by date of last message written by this participant
                            ->orderBy('tm.lastParticipantMessageDate', 'DESC')
                            ->setMaxResults(1);

            return $builder->getQuery()->getOneOrNullResult();
        }


        /**
         * Performs denormalization tricks
         */
        protected function denormalize(ThreadInterface $thread)
        {
            $this->doMetadata($thread);
            $this->doCreatedByAndAt($thread);
            $this->doDatesOfLastMessageWrittenByOtherParticipant($thread);
        }

        /**
         * Ensures that the thread metadata are up to date
         */
        protected function doMetadata(ThreadInterface $thread)
        {
            // Participants
            foreach ($thread->getParticipants() as $participant)
            {
                $meta = $thread->getMetadataForParticipant($participant);
                if (!$meta)
                {
                    $meta = $this->createThreadMetadata();
                    $meta->setParticipant($participant);

                    $thread->addMetadata($meta);
                }
            }

            // Messages
            foreach ($thread->getMessages() as $message)
            {
                $meta = $thread->getMetadataForParticipant($message->getSender());
                if (!$meta)
                {
                    $meta = $this->createThreadMetadata();
                    $meta->setParticipant($message->getSender());
                    $thread->addMetadata($meta);
                }

                $meta->setLastParticipantMessageDate($message->getCreatedAt());
            }
        }

        /**
         * Ensures that the createdBy & createdAt properties are set
         */
        protected function doCreatedByAndAt(ThreadInterface $thread)
        {
            if (!($message = $thread->getFirstMessage()))
            {
                return;
            }

            if (!$thread->getCreatedAt())
            {
                $thread->setCreatedAt($message->getCreatedAt());
            }

            if (!$thread->getCreatedBy())
            {
                $thread->setCreatedBy($message->getSender());
            }
        }

        /**
         * Update the dates of last message written by other participants
         */
        protected function doDatesOfLastMessageWrittenByOtherParticipant(ThreadInterface $thread)
        {
            foreach ($thread->getAllMetadata() as $meta)
            {
                $participantId = $meta->getParticipant()->getId();
                $timestamp     = 0;

                foreach ($thread->getMessages() as $message)
                {
                    if ($participantId != $message->getSender()->getId())
                    {
                        $timestamp = max($timestamp, $message->getTimestamp());
                    }
                }
                if ($timestamp)
                {
                    $date = new \DateTime();
                    $date->setTimestamp($timestamp);
                    $meta->setLastMessageDate($date);
                }
            }
        }

        protected function createThreadMetadata()
        {
            return new ThreadMetadata();
        }
    }