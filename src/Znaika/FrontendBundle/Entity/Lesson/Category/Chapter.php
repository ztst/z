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
    }