<?php

    namespace Znaika\FrontendBundle\Entity\Communication;

    use Doctrine\ORM\Mapping as ORM;
    use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

    class ThreadMetadata extends BaseThreadMetadata
    {
        /**
         * @var integer
         */
        protected $threadMetadataId;

        /**
         * @var \Znaika\FrontendBundle\Entity\Communication\Thread
         */
        protected $thread;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        protected $participant;

        public function getId()
        {
            return $this->threadMetadataId;
        }
    }
