<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\LikesBundle\Entity\VideoCommentLike;

    class VideoComment
    {

        /**
         * @var integer
         */
        private $videoCommentId;

        /**
         * @var string
         */
        private $text;

        /**
         * @var int
         */
        private $commentType;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        private $video;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $user;

        /**
         * @var VideoComment
         */
        private $question;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $answers;

        /**
         * @var bool
         */
        private $isAnswered = false;

        /**
         * @var int
         */
        private $status = 0;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $likes;

        /**
         * @var int
         */
        private $likesCount = 0;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
            $this->likes   = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get videoCommentId
         *
         * @return integer
         */
        public function getVideoCommentId()
        {
            return $this->videoCommentId;
        }

        /**
         * Set text
         *
         * @param string $text
         *
         * @return VideoComment
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
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return VideoComment
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
         * @return VideoComment
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
         * @param \Znaika\ProfileBundle\Entity\User $user
         *
         * @return VideoComment
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
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $question
         */
        public function setQuestion($question)
        {
            $this->question = $question;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment
         */
        public function getQuestion()
        {
            return $this->question;
        }

        public function addAnswer(VideoComment $answer)
        {
            $this->answers[] = $answer;

            return $this;
        }

        public function removeAnswer(VideoComment $answer)
        {
            $this->answers->removeElement($answer);
        }

        public function getAnswers()
        {
            return $this->answers;
        }

        /**
         * @param int $commentType
         */
        public function setCommentType($commentType)
        {
            $this->commentType = $commentType;
        }

        /**
         * @return int
         */
        public function getCommentType()
        {
            return $this->commentType;
        }

        /**
         * @param boolean $isAnswered
         */
        public function setIsAnswered($isAnswered)
        {
            $this->isAnswered = $isAnswered;
        }

        /**
         * @return boolean
         */
        public function getIsAnswered()
        {
            return $this->isAnswered;
        }

        /**
         * @param int $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return int
         */
        public function getStatus()
        {
            return $this->status;
        }

        public function addLike(VideoCommentLike $videoCommentLike)
        {
            $this->likes[] = $videoCommentLike;

            return $this;
        }

        public function removeLike(VideoCommentLike $videoCommentLike)
        {
            $this->likes->removeElement($videoCommentLike);
        }

        public function getLikes()
        {
            return $this->likes;
        }

        /**
         * @param int $likesCount
         */
        public function setLikesCount($likesCount)
        {
            $this->likesCount = $likesCount;
        }

        /**
         * @return int
         */
        public function getLikesCount()
        {
            return $this->likesCount;
        }
    }