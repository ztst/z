<?
    namespace Znaika\ProfileBundle\Entity\Badge;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Entity\User;

    abstract class BaseUserBadge
    {
        /**
         * @var integer
         */
        protected $userBadgeId;

        /**
         * @var integer
         */
        protected $badgeType;

        /**
         * @var User
         */
        protected $user;

        /**
         * @var \DateTime
         */
        protected $createdTime;

        /**
         * @var boolean
         */
        protected $isViewed = false;

        /**
         * @param int $badgeType
         */
        abstract public function setBadgeType($badgeType);

        /**
         * @return int
         */
        abstract public function getBadgeType();

        /**
         * @param \DateTime $createdTime
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;
        }

        /**
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }

        /**
         * @param \Znaika\ProfileBundle\Entity\User $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @return int
         */
        public function getUserBadgeId()
        {
            return $this->userBadgeId;
        }

        /**
         * @param boolean $isViewed
         */
        public function setIsViewed($isViewed)
        {
            $this->isViewed = $isViewed;
        }

        /**
         * @return boolean
         */
        public function getIsViewed()
        {
            return $this->isViewed;
        }
    }