<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Category;

    use Doctrine\ORM\Mapping as ORM;

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
        private $urlName;

        /**
         * @var \DateTime
         */
        private $createdTime;


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
         * Get createdTime
         *
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }
        /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $videos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add videos
     *
     * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $videos
     * @return Subject
     */
    public function addVideo(\Znaika\FrontendBundle\Entity\Lesson\Content\Video $videos)
    {
        $this->videos[] = $videos;
    
        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $videos
     */
    public function removeVideo(\Znaika\FrontendBundle\Entity\Lesson\Content\Video $videos)
    {
        $this->videos->removeElement($videos);
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
}