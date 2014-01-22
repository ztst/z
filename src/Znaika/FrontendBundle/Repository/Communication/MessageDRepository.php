<?
    namespace Znaika\FrontendBundle\Repository\Communication;

    use Doctrine\ORM\EntityRepository;
    use FOS\MessageBundle\Model\MessageInterface;
    use FOS\MessageBundle\Model\ParticipantInterface;
    use FOS\MessageBundle\Model\ReadableInterface;
    use Znaika\FrontendBundle\Entity\Communication\Message;
    use Znaika\FrontendBundle\Entity\Communication\MessageMetadata;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class MessageDBRepository extends EntityRepository implements IMessageRepository
    {
        /**
         * @return MessageInterface
         */
        function createMessage()
        {
            return new Message();
        }

        public function getNbUnreadMessageByParticipant(ParticipantInterface $participant)
        {
            $builder = $this->createQueryBuilder('m');

            return (int)$builder
                ->select($builder->expr()->count('mm.message_metadata_id'))

                ->innerJoin('m.metadata', 'mm')
                ->innerJoin('mm.participant', 'p')

                ->where('p.userId = :participant_id')
                ->setParameter('participant_id', $participant->getId())

                ->andWhere('m.sender != :sender')
                ->setParameter('sender', $participant->getId())

                ->andWhere('mm.isRead = :isRead')
                ->setParameter('isRead', false, \PDO::PARAM_BOOL)

                ->getQuery()
                ->getSingleScalarResult();
        }

        /**
         * @param ReadableInterface $readable
         * @param ParticipantInterface $participant
         */
        public function markAsReadByParticipant(ReadableInterface $readable, ParticipantInterface $participant)
        {
            $readable->setIsReadByParticipant($participant, true);
        }

        /**
         * Marks the readable as unread by this participant
         *
         * @param ReadableInterface $readable
         * @param ParticipantInterface $participant
         */
        public function markAsUnreadByParticipant(ReadableInterface $readable, ParticipantInterface $participant)
        {
            $readable->setIsReadByParticipant($participant, false);
        }

        /**
         * @param MessageInterface $message
         * @param Boolean $andFlush Whether to flush the changes (default true)
         */
        public function saveMessage(MessageInterface $message, $andFlush = true)
        {
            $this->denormalize($message);
            $this->getEntityManager()->persist($message);
            if ($andFlush)
            {
                $this->getEntityManager()->flush();
            }
        }

        /**
         * @return string
         */
        public function getClass()
        {
            return 'ZnaikaFrontendBundle:Communication\Message';
        }

        public function markIsReadByParticipant(MessageInterface $message, ParticipantInterface $participant, $isRead)
        {
            $meta = $message->getMetadataForParticipant($participant);
            if (!$meta || $meta->getIsRead() == $isRead)
            {
                return;
            }

            $this->getEntityManager()->createQueryBuilder()
                      ->update('ZnaikaFrontendBundle:Communication\MessageMetadata', 'm')
                      ->set('m.isRead', '?1')
                      ->setParameter('1', (bool)$isRead, \PDO::PARAM_BOOL)

                      ->where('m.messageMetadataId = :id')
                      ->setParameter('id', $meta->getId())

                      ->getQuery()
                      ->execute();
        }

        /**
         * @param User $participant
         * @param $messageId
         *
         * @return boolean
         */
        public function markIsDeletedByParticipant(User $participant, $messageId)
        {
            $message  = $this->find($messageId);
            $meta = $message->getMetadataForParticipant($participant);
            if (!$meta || $meta->getIsDeleted())
            {
                return false;
            }

            $this->getEntityManager()->createQueryBuilder()
                      ->update('ZnaikaFrontendBundle:Communication\MessageMetadata', 'm')
                      ->set('m.isDeleted', '?1')
                      ->setParameter('1', true, \PDO::PARAM_BOOL)

                      ->where('m.messageMetadataId = :id')
                      ->setParameter('id', $meta->getId())

                      ->getQuery()
                      ->execute();
            return true;
        }

        protected function denormalize(MessageInterface $message)
        {
            $this->doMetadata($message);
        }

        /**
         * Ensures that the message metadata are up to date
         */
        protected function doMetadata(MessageInterface $message)
        {
            foreach ($message->getThread()->getAllMetadata() as $threadMeta)
            {
                $meta = $message->getMetadataForParticipant($threadMeta->getParticipant());
                if (!$meta)
                {
                    $meta = $this->createMessageMetadata();
                    $meta->setParticipant($threadMeta->getParticipant());

                    $message->addMetadata($meta);
                }
            }
        }

        protected function createMessageMetadata()
        {
            return new MessageMetadata();
        }
    }