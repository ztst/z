<?php

    namespace Znaika\FrontendBundle\Entity\Profile;

    use Doctrine\ORM\Mapping as ORM;

    class UserRegistration
    {
        /**
         * @var integer
         */
        private $userRegistrationId;

        /**
         * @var string
         */
        private $registerKey;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        private $user;

        /**
         * Get userRegistrationId
         *
         * @return integer
         */
        public function getUserRegistrationId()
        {
            return $this->userRegistrationId;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return UserRegistration
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;

            return $this;
        }

        /**
         * Get createdTime
         *
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }

        /**
         * Set user
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         *
         * @return UserRegistration
         */
        public function setUser(\Znaika\FrontendBundle\Entity\Profile\User $user = null)
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get user
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * Set registerKey
         *
         * @param string $registerKey
         *
         * @return UserRegistration
         */
        public function setRegisterKey($registerKey)
        {
            $this->registerKey = $registerKey;

            return $this;
        }

        /**
         * Get registerKey
         *
         * @return string
         */
        public function getRegisterKey()
        {
            return $this->registerKey;
        }
    }