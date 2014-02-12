<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Symfony\Component\Validator\ExecutionContextInterface;
    use \Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;

    class Video
    {
        /**
         * @var integer
         */
        private $videoId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var integer
         */
        private $grade;

        /**
         * @var string
         */
        private $urlName;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var string
         */
        private $url;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Category\Subject
         */
        private $subject;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Category\Chapter
         */
        private $chapter;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis
         */
        private $synopsis;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoComments;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoAttachments;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $quizQuestions;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $userAttempts;

        /**
         * @var integer
         */
        private $views = 0;

        /**
         * @var string
         */
        private $smallThumbnailUrl;

        /**
         * @var string
         */
        private $mediumThumbnailUrl;

        /**
         * @var string
         */
        private $largeThumbnailUrl;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videoComments    = new \Doctrine\Common\Collections\ArrayCollection();
            $this->videoAttachments = new \Doctrine\Common\Collections\ArrayCollection();
            $this->userAttempts     = new \Doctrine\Common\Collections\ArrayCollection();
            $this->quizQuestions    = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get videoId
         *
         * @return integer
         */
        public function getVideoId()
        {
            return $this->videoId;
        }

        /**
         * Set name
         *
         * @param string $name
         *
         * @return Video
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name
         *
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Set grade
         *
         * @param integer $grade
         *
         * @return Video
         */
        public function setGrade($grade)
        {
            $this->grade = $grade;

            return $this;
        }

        /**
         * Get grade
         *
         * @return integer
         */
        public function getGrade()
        {
            return $this->grade;
        }

        /**
         * Set urlName
         *
         * @param string $urlName
         *
         * @return Video
         */
        public function setUrlName($urlName)
        {
            $this->urlName = $urlName;

            return $this;
        }

        /**
         * Get urlName
         *
         * @return string
         */
        public function getUrlName()
        {
            return $this->urlName;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return Video
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
         * Set subject
         *
         * @param Subject $subject
         *
         * @return Video
         */
        public function setSubject(Subject $subject = null)
        {
            $this->subject = $subject;

            return $this;
        }

        /**
         * Get subject
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Category\Subject
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * Set url
         *
         * @param string $url
         *
         * @return Video
         */
        public function setUrl($url)
        {
            $this->url = $url;

            return $this;
        }

        /**
         * Get url
         *
         * @return string
         */
        public function getUrl()
        {
            return $this->url;
        }

        /**
         * @param string $largeThumbnailUrl
         */
        public function setLargeThumbnailUrl($largeThumbnailUrl)
        {
            $this->largeThumbnailUrl = $largeThumbnailUrl;
        }

        /**
         * 640x476
         *
         * @return string
         */
        public function getLargeThumbnailUrl()
        {
            return (empty($this->largeThumbnailUrl)) ? $this->getMediumThumbnailUrl() : $this->largeThumbnailUrl;
        }

        /**
         * @param string $mediumThumbnailUrl
         */
        public function setMediumThumbnailUrl($mediumThumbnailUrl)
        {
            $this->mediumThumbnailUrl = $mediumThumbnailUrl;
        }

        /**
         * 200x150
         *
         * @return string
         */
        public function getMediumThumbnailUrl()
        {
            return (empty($this->mediumThumbnailUrl)) ? $this->getSmallThumbnailUrl() : $this->mediumThumbnailUrl;
        }

        /**
         * 100x75
         *
         * @param string $smallThumbnailUrl
         */
        public function setSmallThumbnailUrl($smallThumbnailUrl)
        {
            $this->smallThumbnailUrl = $smallThumbnailUrl;
        }

        /**
         * @return string
         */
        public function getSmallThumbnailUrl()
        {
            return $this->smallThumbnailUrl;
        }

        /**
         * Set chapter
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Category\Chapter $chapter
         *
         * @return Video
         */
        public function setChapter(\Znaika\FrontendBundle\Entity\Lesson\Category\Chapter $chapter = null)
        {
            $this->chapter = $chapter;

            return $this;
        }

        /**
         * Get chapter
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Category\Chapter
         */
        public function getChapter()
        {
            return $this->chapter;
        }

        /**
         * Set synopsis
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis $synopsis
         *
         * @return Video
         */
        public function setSynopsis(\Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis $synopsis = null)
        {
            $this->synopsis = $synopsis;

            return $this;
        }

        /**
         * Get synopsis
         *
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis
         */
        public function getSynopsis()
        {
            return $this->synopsis;
        }

        /**
         * Add videoComments
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         *
         * @return Video
         */
        public function addVideoComment(\Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment)
        {
            $videoComment->setVideo($this);
            $this->videoComments[] = $videoComment;

            return $this;
        }

        /**
         * Remove videoComments
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         */
        public function removeVideoComment(\Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment)
        {
            $this->videoComments->removeElement($videoComment);
        }

        /**
         * Get videoComments
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getVideoComments()
        {
            return $this->videoComments;
        }

        /**
         * Add quizQuestions
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion
         *
         * @return Video
         */
        public function addQuizQuestion(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion)
        {
            $quizQuestion->setVideo($this);
            $this->quizQuestions[] = $quizQuestion;

            return $this;
        }

        /**
         * Remove quizQuestions
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion
         */
        public function removeQuizQuestion(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\QuizQuestion $quizQuestion)
        {
            $this->quizQuestions->removeElement($quizQuestion);
        }

        /**
         * Get quizQuestions
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getQuizQuestions()
        {
            return $this->quizQuestions;
        }

        public function isChapterValid(ExecutionContextInterface $context)
        {
            if ($this->getSubject()->getUrlName() != $this->getChapter()->getSubject()->getUrlName())
            {
                //TODO: text
                $context->addViolationAt('chapter', 'Выбранная глава не соответствует предмету.', array(), null);
            }
            if ($this->getGrade() != $this->getChapter()->getGrade())
            {
                //TODO: text
                $context->addViolationAt('chapter', 'Выбранная глава не соответствует классу.', array(), null);
            }
        }

        /**
         * Add userAttempts
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt $userAttempt
         *
         * @return Video
         */
        public function addUserAttempt(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt $userAttempt)
        {
            $this->userAttempts[] = $userAttempt;

            return $this;
        }

        /**
         * Remove userAttempts
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt $userAttempt
         */
        public function removeUserAttempt(\Znaika\FrontendBundle\Entity\Lesson\Content\Quiz\Attempt\UserAttempt $userAttempt)
        {
            $this->userAttempts->removeElement($userAttempt);
        }

        /**
         * Get userAttempts
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getUserAttempts()
        {
            return $this->userAttempts;
        }

        /**
         * @param int $views
         */
        public function setViews($views)
        {
            $this->views = $views;
        }

        /**
         * @return int
         */
        public function getViews()
        {
            return $this->views;
        }

        /**
         * @param VideoAttachment $videoAttachment
         *
         * @return Video
         */
        public function addVideoAttachment(VideoAttachment $videoAttachment)
        {
            $videoAttachment->setVideo($this);
            $this->videoAttachments[] = $videoAttachment;

            return $this;
        }

        /**
         * @param VideoAttachment $videoAttachment
         */
        public function removeVideoAttachment(VideoAttachment $videoAttachment)
        {
            $this->videoAttachments->removeElement($videoAttachment);
        }

        /**
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getVideoAttachments()
        {
            return $this->videoAttachments;
        }
    }