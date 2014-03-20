<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Category;

    use Doctrine\ORM\Mapping as ORM;

    class Chapter
    {
        /**
         * @var integer
         */
        private $chapterId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $urlName;

        /**
         * @var integer
         */
        private $grade;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Category\Subject
         */
        private $subject;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videos;

        /**
         * @var integer
         */
        private $orderPriority;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get chapterId
         *
         * @return integer
         */
        public function getChapterId()
        {
            return $this->chapterId;
        }

        /**
         * Set name
         *
         * @param string $name
         *
         * @return Chapter
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
         * Set urlName
         *
         * @param string $urlName
         *
         * @return Chapter
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
         * Set grade
         *
         * @param integer $grade
         *
         * @return Chapter
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
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return Chapter
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
         * @param \Znaika\FrontendBundle\Entity\Lesson\Category\Subject $subject
         *
         * @return Chapter
         */
        public function setSubject(\Znaika\FrontendBundle\Entity\Lesson\Category\Subject $subject = null)
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
         * Add videos
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         *
         * @return Subject
         */
        public function addVideo(\Znaika\FrontendBundle\Entity\Lesson\Content\Video $video)
        {
            $this->videos[] = $video;

            return $this;
        }

        /**
         * Remove videos
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         */
        public function removeVideo(\Znaika\FrontendBundle\Entity\Lesson\Content\Video $video)
        {
            $this->videos->removeElement($video);
        }

        /**
         * Get videos
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getVideos()
        {
            return $this->videos;
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
    }