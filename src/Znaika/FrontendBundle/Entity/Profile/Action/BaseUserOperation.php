<?
    namespace Znaika\FrontendBundle\Entity\Profile\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\User;

    abstract class BaseUserOperation
    {
        /**
         * @var Video
         */
        protected $video;

        /**
         * @var integer
         */
        protected $userOperationId;

        /**
         * @var integer
         */
        protected $operationType;

        /**
         * @var User
         */
        protected $user;

        /**
         * @var \DateTime
         */
        protected $createdTime;

        /**
         * @var integer
         */
        protected $socialNetwork;

        /**
         * @param int $type
         */
        abstract public function setOperationType($type);

        /**
         * @return int
         */
        abstract public function getOperationType();

        abstract public function getAccruedPoints();

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
         * @return int
         */
        public function getUserOperationId()
        {
            return $this->userOperationId;
        }

        /**
         * @param User $user
         *
         * @return BaseUserOperation
         */
        public function setUser(User $user = null)
        {
            $this->user = $user;
            $this->addUserPoints();

            return $this;
        }

        /**
         * @return User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         */
        public function setVideo($video)
        {
            $this->video = $video;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        public function getVideo()
        {
            return $this->video;
        }

        /**
         * @param int $socialNetwork
         */
        public function setSocialNetwork($socialNetwork)
        {
            $this->socialNetwork = $socialNetwork;
        }

        /**
         * @return int
         */
        public function getSocialNetwork()
        {
            return $this->socialNetwork;
        }

        protected function addUserPoints()
        {
            if (is_null($this->user))
            {
                return;
            }

            $userPoints = $this->user->getPoints();
            $accruedPoints = $this->getAccruedPoints();

            $this->user->setPoints($userPoints + $accruedPoints);
        }
    }