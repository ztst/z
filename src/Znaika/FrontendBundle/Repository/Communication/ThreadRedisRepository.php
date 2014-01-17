<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use FOS\MessageBundle\Model\ParticipantInterface;
    use FOS\MessageBundle\Model\ReadableInterface;
    use FOS\MessageBundle\Model\ThreadInterface;
    use FOS\MessageBundle\ModelManager\Builder;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class ThreadRedisRepository implements IThreadRepository
    {
        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
            // TODO: Implement markAsReadByParticipant() method.
        }

        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
            // TODO: Implement markAsUnreadByParticipant() method.
        }

        /**
         * @param $id
         *
         * @return ThreadInterface|null
         */
        public function findThreadById($id)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return Builder a query builder suitable for pagination
         */
        public function getParticipantInboxThreadsQueryBuilder(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findParticipantInboxThreads(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return Builder a query builder suitable for pagination
         */
        public function getParticipantSentThreadsQueryBuilder(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findParticipantSentThreads(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return Builder a query builder suitable for pagination
         */
        public function getParticipantDeletedThreadsQueryBuilder(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return ThreadInterface[]
         */
        public function findParticipantDeletedThreads(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         * @param string $search
         *
         * @return Builder a query builder suitable for pagination
         */
        public function getParticipantThreadsBySearchQueryBuilder(ParticipantInterface $participant, $search)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         * @param string $search
         *
         * @return array of ThreadInterface
         */
        public function findParticipantThreadsBySearch(ParticipantInterface $participant, $search)
        {
            return null;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findThreadsCreatedBy(ParticipantInterface $participant)
        {
            return null;
        }

        /**
         * @return ThreadInterface
         */
        public function createThread()
        {
            return null;
        }

        /**
         * @param ThreadInterface $thread
         * @param Boolean $andFlush Whether to flush the changes (default true)
         */
        public function saveThread(ThreadInterface $thread, $andFlush = true)
        {
            // TODO: Implement saveThread() method.
        }

        /**
         * @param ThreadInterface $thread the thread to delete
         */
        public function deleteThread(ThreadInterface $thread)
        {
            // TODO: Implement deleteThread() method.
        }

        /**
         * @param User $participant
         *
         * @return array of ThreadInterface
         */
        public function findParticipantAllThreads(User $participant)
        {
            return null;
        }

        /**
         * @param User $firstUser
         * @param User $secondUser
         *
         * @return ThreadInterface
         */
        public function findThreadByUsers(User $firstUser, User $secondUser)
        {
            return null;
        }
    }