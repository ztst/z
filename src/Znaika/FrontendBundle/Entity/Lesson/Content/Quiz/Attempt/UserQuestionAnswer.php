<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt;

    use Doctrine\ORM\Mapping as ORM;

    class UserQuestionAnswer
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
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt
         */
        private $userAttempt;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion
         */
        private $quizQuestion;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer
         */
        private $quizAnswer;

        /**
         * @return string
         */
        public function getQuizQuestionText()
        {
            return $this->quizQuestion->getText();
        }

        /**
         * @param $text
         */
        public function setQuizQuestionText($text)
        {
            $this->quizQuestion->setText($text);
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
         * @return UserQuestionAnswer
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
         * Set userAttempt
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt $userAttempt
         *
         * @return UserQuestionAnswer
         */
        public function setUserAttempt(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt $userAttempt = null)
        {
            $this->userAttempt = $userAttempt;

            return $this;
        }

        /**
         * Get userAttempt
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt
         */
        public function getUserAttempt()
        {
            return $this->userAttempt;
        }

        /**
         * Set quizQuestion
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion
         *
         * @return UserQuestionAnswer
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
         * Set quizAnswer
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer
         *
         * @return UserQuestionAnswer
         */
        public function setQuizAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer = null)
        {
            $this->quizAnswer = $quizAnswer;

            return $this;
        }

        /**
         * Get quizAnswer
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer
         */
        public function getQuizAnswer()
        {
            return $this->quizAnswer;
        }
    }