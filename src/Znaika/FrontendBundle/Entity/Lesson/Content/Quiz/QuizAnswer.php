<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Quiz;

    use Doctrine\ORM\Mapping as ORM;

    class QuizAnswer
    {
        /**
         * @var integer
         */
        private $quizAnswerId;

        /**
         * @var string
         */
        private $text;

        /**
         * @var boolean
         */
        private $isRight;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion
         */
        private $quizQuestion;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $userQuestionAnswers;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->userQuestionAnswers = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get quizAnswerId
         *
         * @return integer
         */
        public function getQuizAnswerId()
        {
            return $this->quizAnswerId;
        }

        /**
         * Set text
         *
         * @param string $text
         *
         * @return QuizAnswer
         */
        public function setText($text)
        {
            $this->text = $text;

            return $this;
        }

        /**
         * Get text
         *
         * @return string
         */
        public function getText()
        {
            return $this->text;
        }

        /**
         * Set isRight
         *
         * @param boolean $isRight
         *
         * @return QuizAnswer
         */
        public function setIsRight($isRight)
        {
            $this->isRight = $isRight;

            return $this;
        }

        /**
         * Get isRight
         *
         * @return boolean
         */
        public function getIsRight()
        {
            return $this->isRight;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return QuizAnswer
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
         * Set quizQuestion
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion
         *
         * @return QuizAnswer
         */
        public function setQuizQuestion(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion = null)
        {
            $this->quizQuestion = $quizQuestion;

            return $this;
        }

        /**
         * Get quizQuestion
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion
         */
        public function getQuizQuestion()
        {
            return $this->quizQuestion;
        }

        /**
         * Add userQuestionAnswers
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswers
         *
         * @return QuizAnswer
         */
        public function addUserQuestionAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswers)
        {
            $this->userQuestionAnswers[] = $userQuestionAnswers;

            return $this;
        }

        /**
         * Remove userQuestionAnswers
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswers
         */
        public function removeUserQuestionAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserQuestionAnswer $userQuestionAnswers)
        {
            $this->userQuestionAnswers->removeElement($userQuestionAnswers);
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
    }