<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Symfony\Component\Validator\ExecutionContextInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Category\Chapter;
    use \Znaika\FrontendBundle\Entity\Lesson\Category\Subject;
    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\ProfileBundle\Entity\User;

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
         * @var integer
         */
        private $views = 0;

        /**
         * @var string
         */
        private $author;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $supervisors;

        /**
         * @var integer
         */
        private $orderPriority;

        /**
         * @var Quiz
         */
        private $quiz;

        /**
         * @var integer
         */
        private $duration = 0;

        /**
         * @var string
         */
        private $contentDir;

        /**
         * @var int
         */
        private $likesCount = 0;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $likes;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videoComments    = new \Doctrine\Common\Collections\ArrayCollection();
            $this->videoAttachments = new \Doctrine\Common\Collections\ArrayCollection();
            $this->supervisors      = new \Doctrine\Common\Collections\ArrayCollection();
            $this->likes            = new \Doctrine\Common\Collections\ArrayCollection();
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
            $synopsis->setVideo($this);
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

        /**
         * @param string $author
         */
        public function setAuthor($author)
        {
            $this->author = $author;
        }

        /**
         * @return string
         */
        public function getAuthor()
        {
            return $this->author;
        }

        /**
         * @param User $supervisor
         *
         * @return $this
         */
        public function addSuperVisor(User $supervisor)
        {
            $this->supervisors[] = $supervisor;

            return $this;
        }

        public function removeSupervisor(User $supervisor)
        {
            $this->supervisors->removeElement($supervisor);
        }

        /**
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getSupervisors()
        {
            return $this->supervisors;
        }

        public function setInfoFromChapter(Chapter $chapter)
        {
            $this->setChapter($chapter);
            $this->setGrade($chapter->getGrade());
            $this->setSubject($chapter->getSubject());
        }

        /**
         * @param int $orderPriority
         */
        public function setOrderPriority($orderPriority)
        {
            $this->orderPriority = $orderPriority;
        }

        /**
         * @return int
         */
        public function getOrderPriority()
        {
            return $this->orderPriority;
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
         * @param int $duration
         */
        public function setDuration($duration)
        {
            $this->duration = $duration;
        }

        /**
         * @return int
         */
        public function getDuration()
        {
            return $this->duration;
        }

        /**
         * @param string $contentDir
         */
        public function setContentDir($contentDir)
        {
            $this->contentDir = $contentDir;
        }

        /**
         * @return string
         */
        public function getContentDir()
        {
            return $this->contentDir;
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

        public function addLike(VideoLike $videoLike)
        {
            $this->likes[] = $videoLike;

            return $this;
        }

        public function removeLike(VideoLike $videoLike)
        {
            $this->likes->removeElement($videoLike);
        }

        /**
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getLikes()
        {
            return $this->likes;
        }
    }