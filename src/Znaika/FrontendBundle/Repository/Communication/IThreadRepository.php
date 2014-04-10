<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use FOS\MessageBundle\Model\ThreadInterface;
    use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
    use Znaika\ProfileBundle\Entity\User;

    interface IThreadRepository extends ThreadManagerInterface
    {
        /**
         * @param User $participant
         * @return ThreadInterface[]
         */
        public function findParticipantAllThreads(User $participant);

        /**
         * @param User $firstUser
         * @param User $secondUser
         *
         * @return ThreadInterface
         */
        public function findThreadByUsers(User $firstUser, User $secondUser);
    }
