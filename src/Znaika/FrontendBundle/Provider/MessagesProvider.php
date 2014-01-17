<?
    namespace Znaika\FrontendBundle\Provider;

    use FOS\MessageBundle\Model\ThreadInterface;
    use FOS\MessageBundle\Provider\Provider;
    use Znaika\FrontendBundle\Entity\Profile\User;
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

            return $this->threadManager->findThreadByUsers($authenticatedParticipant, $participant);
        }

        /**
         * @return ThreadInterface[]
         */
        public function getParticipantAllThreads()
        {
            $authenticatedParticipant = $this->getAuthenticatedParticipant();

            return $this->threadManager->findParticipantAllThreads($authenticatedParticipant);
        }
    }