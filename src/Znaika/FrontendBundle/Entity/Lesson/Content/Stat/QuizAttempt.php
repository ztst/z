<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Stat;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\ProfileBundle\Entity\User;

    class QuizAttempt
    {
        /**
         * @var integer
         */
        private $quizAttemptId;
        /**
         * @var double
         */
        private $score;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var Quiz
         */
        private $quiz;

        /**
         * @var User
         */
        private $user;

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
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz $quiz
         */
        public function setQuiz($quiz)
        {
            $this->quiz = $quiz;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz
         */
        public function getQuiz()
        {
            return $this->quiz;
        }

        /**
         * @param int $quizAttemptId
         */
        public function setQuizAttemptId($quizAttemptId)
        {
            $this->quizAttemptId = $quizAttemptId;
        }

        /**
         * @return int
         */
        public function getQuizAttemptId()
        {
            return $this->quizAttemptId;
        }

        /**
         * @param float $score
         */
        public function setScore($score)
        {
            $this->score = $score;
        }

        /**
         * @return float
         */
        public function getScore()
        {
            return $this->score;
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
    }