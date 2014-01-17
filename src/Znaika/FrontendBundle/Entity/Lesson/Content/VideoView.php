<?php

    namespace Znaika\FrontendBundle\Entity\Lesson\Content;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * VideoView
     */
    class VideoView
    {
        /**
         * @var integer
         */
        private $videoViewId;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\FrontendBundle\Entity\Lesson\Content\Video
         */
        private $video;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        private $user;

        /**
         * Get videoViewId
         *
         * @return integer
         */
        public function getVideoViewId()
        {
            return $this->videoViewId;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return VideoView
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
         * @return VideoView
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
         * Set user
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         *
         * @return VideoView
         */
        public function setUser(\Znaika\FrontendBundle\Entity\Profile\User $user = null)
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get user
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\User
         */
        public function getUser()
        {
            return $this->user;
        }
    }