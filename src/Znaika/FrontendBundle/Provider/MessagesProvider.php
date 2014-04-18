<?
    namespace Znaika\FrontendBundle\Provider;

    use FOS\MessageBundle\Model\ThreadInterface;
    use FOS\MessageBundle\Provider\Provider;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Repository\Communication\IThreadRepository;

    class MessagesProvider extends Provider
    {
        /**
         * @var IThreadRepository
         */
        protected $threadManager;

        /**
         * @param User $participant
         *
         * @return ThreadInterface
         */
        public function getThreadWithUser($participant)
        {
            $authenticatedParticipant = $this->getAuthenticatedParticipant();
            $thread = $this->threadManager->findThreadByUsers($authenticatedParticipant, $participant);

            if ($thread)
            {
                //TODO: make 1 query solution
                $thread->getMessages();
                $this->threadReader->markAsRead($thread);
            }

            return $thread;
        }

        /**
         * @param $filter
         *
         * @return ThreadInterface[]
         */
        public function getParticipantAllThreads($filter)
        {
            $authenticatedParticipant = $this->getAuthenticatedParticipant();

            return $this->threadManager->findParticipantAllThreads($authenticatedParticipant, $filter);
        }
    }