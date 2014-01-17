<?php

    namespace Znaika\FrontendBundle\Entity\Communication;

    use Doctrine\ORM\Mapping as ORM;
    use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

    class MessageMetadata extends BaseMessageMetadata
    {
        /**
         * @var integer
         */
        protected $messageMetadataId;

        /**
         * @var boolean
         */
        protected $isDeleted = false;

        /**
         * @var \Znaika\FrontendBundle\Entity\Communication\Message
         */
        protected $message;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        protected $participant;

        public function getId()
        {
            return $this->messageMetadataId;
        }

        /**
         * @param boolean $isDeleted
         */
        public function setIsDeleted($isDeleted)
        {
            $this->isDeleted = (boolean)$isDeleted;
        }

        /**
         * @return boolean
         */
        public function getIsDeleted()
        {
            return $this->isDeleted;
        }
    }
