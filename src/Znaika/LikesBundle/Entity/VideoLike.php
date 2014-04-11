<?
    namespace Znaika\LikesBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    class VideoLike
    {
        /**
         * @var integer
         */
        private $videoLikeId;

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
         * @param \Znaika\ProfileBundle\Entity\User $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getUser()
        {
            return $this->user;
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

        /**
         * @param int $videoLikeId
         */
        public function setVideoLikeId($videoLikeId)
        {
            $this->videoLikeId = $videoLikeId;
        }

        /**
         * @return int
         */
        public function getVideoLikeId()
        {
            return $this->videoLikeId;
        }
    }