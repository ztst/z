<?php

    namespace Znaika\FrontendBundle\Entity\Communication;


    use Doctrine\ORM\Mapping as ORM;
    use FOS\MessageBundle\Entity\Thread as BaseThread;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class Thread extends BaseThread
    {
        /**
         * @var integer
         */
        protected $threadId;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $messages;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $metadata;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        protected $createdBy;

        public function getId()
        {
            return $this->threadId;
        }

        /**
         * @param User $user
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getNotDeletedMessages(User $user)
        {
            return $this->messages->filter(
                function (Message $message) use ($user)
                {
                  $metadata = $message->getMetadataForParticipant($user);

                  return (!empty($metadata) && !$metadata->getIsDeleted());
                }
            );
        }

        /**
         * @param User $user
         *
         * @return \FOS\MessageBundle\Model\MessageInterface|mixed
         */
        public function getNotDeletedLastMessage(User $user)
        {
            $messages = $this->getNotDeletedMessages($user);
            return $messages->last();
        }
    }
