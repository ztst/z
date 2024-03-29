<?

    namespace Znaika\ProfileBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    class PasswordRecovery
    {
        const EXPIRED_TIME = "P3D";
        /**
         * @var integer
         */
        private $passwordRecoveryId;

        /**
         * @var string
         */
        private $recoveryKey;

        /**
         * @var \DateTime
         */
        private $recoveryTime;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $user;

        /**
         * Get passwordRecoveryId
         *
         * @return integer
         */
        public function getPasswordRecoveryId()
        {
            return $this->passwordRecoveryId;
        }

        /**
         * Set recoveryTime
         *
         * @param \DateTime $recoveryTime
         *
         * @return PasswordRecovery
         */
        public function setRecoveryTime($recoveryTime)
        {
            $this->recoveryTime = $recoveryTime;

            return $this;
        }

        /**
         * Get recoveryTime
         *
         * @return \DateTime
         */
        public function getRecoveryTime()
        {
            return $this->recoveryTime;
        }

        /**
         * Set user
         *
         * @param \Znaika\ProfileBundle\Entity\User $user
         *
         * @return PasswordRecovery
         */
        public function setUser(\Znaika\ProfileBundle\Entity\User $user = null)
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get user
         *
         * @return \Znaika\ProfileBundle\Entity\User
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
        public function setRecoveryKey($recoveryKey)
        {
            $this->recoveryKey = $recoveryKey;

            return $this;
        }

        /**
         * Get recoveryKey
         *
         * @return string
         */
        public function getRecoveryKey()
        {
            return $this->recoveryKey;
        }
    }