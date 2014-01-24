<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Attachment;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    class VideoAttachment
    {
        /**
         * @var integer
         */
        private $videoAttachmentId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $realName;

        /**
         * @var Video
         */
        private $video;

        /**
         * @var \DateTime
         */
        private $createdTime;

        private $file;

        /**
         * Sets file.
         *
         * @param UploadedFile $file
         */
        public function setFile(UploadedFile $file = null)
        {
            $this->file = $file;
        }

        /**
         * Get file.
         *
         * @return UploadedFile
         */
        public function getFile()
        {
            return $this->file;
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param string $realName
         */
        public function setRealName($realName)
        {
            $this->realName = $realName;
        }

        /**
         * @return string
         */
        public function getRealName()
        {
            return $this->realName;
        }

        /**
         * @return string
         */
        public function getPath()
        {
            return $this->video->getVideoId() . "/" . $this->name;
        }

        /**
         * @param int $videoAttachmentId
         */
        public function setVideoAttachmentId($videoAttachmentId)
        {
            $this->videoAttachmentId = $videoAttachmentId;
        }

        /**
         * @return int
         */
        public function getVideoAttachmentId()
        {
            return $this->videoAttachmentId;
        }

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
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         */
        public function setVideo($video)
        {
            $this->video = $video;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        public function getVideo()
        {
            return $this->video;
        }
    }