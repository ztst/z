<?
    namespace Znaika\ProfileBundle\Entity\Ban;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Entity\User;

    class Info
    {
        /**
         * @var integer
         */
        private $infoId;

        /**
         * @var integer
         */
        private $reason;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $user;

        /**
         * Get passwordRecoveryId
         *
         * @return integer
         */
        public function getInfoId()
        {
            return $this->infoId;
        }

        /**
         * Set recoveryTime
         *
         * @param \DateTime $recoveryTime
         *
         * @return PasswordRecovery
         */
        public function setCreatedTime($recoveryTime)
        {
            $this->createdTime = $recoveryTime;

            return $this;
        }

        /**
         * Get recoveryTime
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
         * @param User $user
         *
         * @return PasswordRecovery
         */
        public function setUser(User $user = null)
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get user
         *
         * @return User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * Set recoveryKey
         *
         * @param string $recoveryKey
         *
         * @return PasswordRecovery
         */
        public function setReason($recoveryKey)
        {
            $this->reason = $recoveryKey;

            return $this;
        }

        /**
         * Get recoveryKey
         *
         * @return string
         */
        public function getReason()
        {
            return $this->reason;
        }
    }