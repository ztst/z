<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt;

    use Doctrine\ORM\Mapping as ORM;

    class UserQuestionAnswer
    {
        /**
         * @var integer
         */
        private $userQuestionAnswerId;

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
         * @var \Doctrine\Common\Collections\Collection
         */
        private $quizAnswers;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->quizAnswers = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get userQuestionAnswerId
         *
         * @return integer
         */
        public function getUserQuestionAnswerId()
        {
            return $this->userQuestionAnswerId;
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
         * Add quizAnswer
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer
         *
         * @return UserQuestionAnswer
         */
        public function addQuizAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer)
        {
            $quizAnswer->addUserQuestionAnswer($this);
            $this->quizAnswers[] = $quizAnswer;

            return $this;
        }

        /**
         * Remove quizAnswer
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer
         */
        public function removeQuizAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer)
        {
            $this->quizAnswers->removeElement($quizAnswer);
        }

        /**
         * Get quizAnswers
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getQuizAnswers()
        {
            return $this->quizAnswers;
        }

        /**
         * @param $quizAnswers
         *
         * @return $this
         */
        public function setQuizAnswers($quizAnswers)
        {
            if ( $quizAnswers )
            {
                if ( is_array($quizAnswers) )
                {
                    foreach ( $quizAnswers as $answer )
                    {
                        $this->addQuizAnswer($answer);
                    }
                }
                else
                {
                    $this->addQuizAnswer($quizAnswers);
                }
            }

            return $this;
        }
    }