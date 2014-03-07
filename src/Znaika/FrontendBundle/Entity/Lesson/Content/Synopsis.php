<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    class Synopsis
    {
        /**
         * @var integer
         */
        private $synopsisId;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        private $video;
        /**
         * @var UploadedFile
         */
        private $msWordFile;

        /**
         * @var UploadedFile
         */
        private $htmlFile;

        /**
         * @var string
         */
        private $htmlFileName;

        /**
         * @var string
         */
        private $locationName;

        /**
         * @param \Symfony\Component\HttpFoundation\File\UploadedFile $htmlFile
         */
        public function setHtmlFile($htmlFile)
        {
            $this->htmlFile = $htmlFile;
        }

        /**
         * @return \Symfony\Component\HttpFoundation\File\UploadedFile
         */
        public function getHtmlFile()
        {
            return $this->htmlFile;
        }

        /**
         * @param \Symfony\Component\HttpFoundation\File\UploadedFile $msWordFile
         */
        public function setMsWordFile($msWordFile)
        {
            $this->msWordFile = $msWordFile;
        }

        /**
         * @return \Symfony\Component\HttpFoundation\File\UploadedFile
         */
        public function getMsWordFile()
        {
            return $this->msWordFile;
        }

        /**
         * Get synopsisId
         *
         * @return integer
         */
        public function getSynopsisId()
        {
            return $this->synopsisId;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return Synopsis
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
         * @return Synopsis
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
         * @param string $name
         */
        public function setHtmlFileName($name)
        {
            $this->htmlFileName = $name;
        }

        /**
         * @return string
         */
        public function getHtmlFileName()
        {
            return $this->htmlFileName;
        }

        /**
         * @param string $locationName
         */
        public function setLocationName($locationName)
        {
            $this->locationName = $locationName;
        }

        /**
         * @return string
         */
        public function getLocationName()
        {
            return $this->locationName;
        }
    }