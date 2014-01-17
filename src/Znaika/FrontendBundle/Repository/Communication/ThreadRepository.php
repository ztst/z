<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use FOS\MessageBundle\Model\ParticipantInterface;
    use FOS\MessageBundle\Model\ReadableInterface;
    use FOS\MessageBundle\Model\ThreadInterface;
    use FOS\MessageBundle\ModelManager\Builder;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Repository\BaseRepository;

    class ThreadRepository extends BaseRepository implements IThreadRepository
    {
        /**
         * @var IThreadRepository
         */
        protected $dbRepository;

        /**
         * @var IThreadRepository
         */
        protected $redisRepository;

        public function __construct($doctrine)
        {
            $redisRepository = new ThreadRedisRepository();
            $dbRepository = $doctrine->getRepository('ZnaikaFrontendBundle:Communication\Thread');

            $this->setRedisRepository($redisRepository);
            $this->setDBRepository($dbRepository);
        }

        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
            $this->redisRepository->markAsReadByParticipant($readable, $user);
            $this->dbRepository->markAsReadByParticipant($readable, $user);
        }

        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $user
         */
        public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $user)
        {
            $this->redisRepository->markAsUnreadByParticipant($readable, $user);
            $this->dbRepository->markAsUnreadByParticipant($readable, $user);
        }

        /**
         * @param $id
         *
         * @return ThreadInterface|void
         */
        public function findThreadById($id)
        {
            $result = $this->redisRepository->findThreadById($id);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findThreadById($id);
            }
            return $result;
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
            $result = $this->redisRepository->findParticipantInboxThreads($participant);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findParticipantInboxThreads($participant);
            }
            return $result;
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
            $result = $this->redisRepository->findParticipantSentThreads($participant);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findParticipantSentThreads($participant);
            }
            return $result;
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
            $result = $this->redisRepository->findParticipantDeletedThreads($participant);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findParticipantDeletedThreads($participant);
            }
            return $result;
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
            $result = $this->redisRepository->getParticipantThreadsBySearchQueryBuilder($participant, $search);
            if ( empty($result) )
            {
                $result = $this->dbRepository->getParticipantThreadsBySearchQueryBuilder($participant, $search);
            }
            return $result;
        }

        /**
         * @param ParticipantInterface $participant
         *
         * @return array of ThreadInterface
         */
        public function findThreadsCreatedBy(ParticipantInterface $participant)
        {
            $result = $this->redisRepository->findThreadsCreatedBy($participant);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findThreadsCreatedBy($participant);
            }
            return $result;
        }

        /**
         * @return ThreadInterface
         */
        public function createThread()
        {
            $result = $this->redisRepository->createThread();
            if ( empty($result) )
            {
                $result = $this->dbRepository->createThread();
            }
            return $result;
        }

        /**
         * @param ThreadInterface $thread
         * @param Boolean $andFlush Whether to flush the changes (default true)
         */
        public function saveThread(ThreadInterface $thread, $andFlush = true)
        {
            $this->redisRepository->saveThread($thread, $andFlush);
            $this->dbRepository->saveThread($thread, $andFlush);
        }

        /**
         * @param ThreadInterface $thread the thread to delete
         */
        public function deleteThread(ThreadInterface $thread)
        {
            $this->redisRepository->deleteThread($thread);
            $this->dbRepository->deleteThread($thread);
        }

        /**
         * @param User $participant
         *
         * @return ThreadInterface[]
         */
        public function findParticipantAllThreads(User $participant)
        {
            $result = $this->redisRepository->findParticipantAllThreads($participant);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findParticipantAllThreads($participant);
            }
            return $result;
        }

        /**
         * @param User $firstUser
         * @param User $secondUser
         *
         * @return ThreadInterface
         */
        public function findThreadByUsers(User $firstUser, User $secondUser)
        {
            $result = $this->redisRepository->findThreadByUsers($firstUser, $secondUser);
            if ( empty($result) )
            {
                $result = $this->dbRepository->findThreadByUsers($firstUser, $secondUser);
            }
            var_dump($result->getId());
            return $result;
        }

    }