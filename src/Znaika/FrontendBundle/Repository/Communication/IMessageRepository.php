<?
    namespace Znaika\FrontendBundle\Repository\Communication;


    use FOS\MessageBundle\ModelManager\MessageManagerInterface;
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
    }
