<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Quiz;

    use Doctrine\ORM\Mapping as ORM;

    class QuizQuestion
    {
        /**
         * @var integer
         */
        private $quizQuestionId;

        /**
         * @var string
         */
        private $text;

        /**
         * @var integer
         */
        private $type;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        private $video;

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
         * Get quizQuestionId
         *
         * @return integer
         */
        public function getQuizQuestionId()
        {
            return $this->quizQuestionId;
        }

        /**
         * Set text
         *
         * @param string $text
         *
         * @return QuizQuestion
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
         * Set type
         *
         * @param integer $type
         *
         * @return QuizQuestion
         */
        public function setType($type)
        {
            $this->type = $type;

            return $this;
        }

        /**
         * Get type
         *
         * @return integer
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return QuizQuestion
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
         * Set video
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         *
         * @return QuizQuestion
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
         * Add quizAnswers
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer
         *
         * @return QuizQuestion
         */
        public function addQuizAnswer(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizAnswer $quizAnswer)
        {
            $quizAnswer->setQuizQuestion($this);
            $this->quizAnswers[] = $quizAnswer;

            return $this;
        }

        /**
         * Remove quizAnswers
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
         * Get quizAnswers
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getRightAnswers()
        {
            $rightAnswers = array();
            foreach ($this->quizAnswers as $answer)
            {
                if ($answer->getIsRight())
                {
                    array_push($rightAnswers, $answer);
                }
            }

            return $rightAnswers;
        }
    }