<?
    namespace Znaika\FrontendBundle\Entity\Lesson\Content\Attachment;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    class Quiz
    {
        /**
         * @var integer
         */
        private $quizId;

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
         * @param int $videoAttachmentId
         */
        public function setQuizId($videoAttachmentId)
        {
            $this->quizId = $videoAttachmentId;
        }

        /**
         * @return int
         */
        public function getQuizId()
        {
            return $this->quizId;
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