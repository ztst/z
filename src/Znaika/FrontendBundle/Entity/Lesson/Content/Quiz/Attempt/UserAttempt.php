<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt;

    use Doctrine\ORM\Mapping as ORM;

    class UserAttempt
    {

        /**
         * @var integer
         */
        private $userAttemptId;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $userQuestionAnswers;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        private $video;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        private $user;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->userQuestionAnswers = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get userAttemptId
         *
         * @return integer
         */
        public function getUserAttemptId()
        {
            return $this->userAttemptId;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return UserAttempt
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
         * Add userQuestionAnswers
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswer
         *
         * @return UserAttempt
         */
        public function addUserQuestionAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswer)
        {
            $userQuestionAnswer->setUserAttempt($this);
            $this->userQuestionAnswers[] = $userQuestionAnswer;

            return $this;
        }

        /**
         * Remove userQuestionAnswers
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswer
         */
        public function removeUserQuestionAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswer)
        {
            $this->userQuestionAnswers->removeElement($userQuestionAnswer);
        }

        /**
         * Get userQuestionAnswers
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getUserQuestionAnswers()
        {
            return $this->userQuestionAnswers;
        }

        /**
         * Set video
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         *
         * @return UserAttempt
         */
        public function setVideo(\Znaika\FrontendBundle\Entity\Lesson\Content\Video $video = null)
        {
            $this->video = $video;

            return $this;
        }

        /**
         * Get video
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        public function getVideo()
        {
            return $this->video;
        }

        /**
         * Set user
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         *
         * @return UserAttempt
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
    }