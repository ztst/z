<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Category;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Entity\User;

    class Subject
    {

        /**
         * @var integer
         */
        private $subjectId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $nameInGenitiveCase;

        /**
         * @var string
         */
        private $urlName;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $chapters;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videos;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $teachers;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->chapters = new \Doctrine\Common\Collections\ArrayCollection();
            $this->videos   = new \Doctrine\Common\Collections\ArrayCollection();
            $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
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
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return Subject
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;

            return $this;
        }

        /**
         * Get subjectId
         *
         * @return integer
         */
        public function getSubjectId()
        {
            return $this->subjectId;
        }

        /**
         * Set name
         *
         * @param string $name
         *
         * @return Subject
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
         * @return Subject
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
         * Add chapters
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Category\Chapter $chapters
         *
         * @return Subject
         */
        public function addChapter(\Znaika\FrontendBundle\Entity\Lesson\Category\Chapter $chapters)
        {
            $this->chapters[] = $chapters;

            return $this;
        }

        /**
         * Remove chapters
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Category\Chapter $chapters
         */
        public function removeChapter(\Znaika\FrontendBundle\Entity\Lesson\Category\Chapter $chapters)
        {
            $this->chapters->removeElement($chapters);
        }

        /**
         * Get chapters
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getChapters()
        {
            return $this->chapters;
        }

        public function addTeacher(User $teacher)
        {
            $this->teachers[] = $teacher;

            return $this;
        }

        public function removeTeacher(User $teacher)
        {
            $this->teachers->removeElement($teacher);
        }

        public function getTeachers()
        {
            return $this->teachers;
        }

        public function setNameInGenitiveCase($nameInGenitiveCase)
        {
            $this->nameInGenitiveCase = $nameInGenitiveCase;
        }

        public function getNameInGenitiveCase()
        {
            return $this->nameInGenitiveCase;
        }
    }