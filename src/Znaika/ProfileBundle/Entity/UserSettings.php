<?php

    namespace Znaika\ProfileBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    class UserSettings
    {
        /**
         * @var integer
         */
        private $userSettingsId;

        /**
         * @var bool
         */
        private $showUserPage = true;

        /**
         * @var bool
         */
        private $showUserRating = true;

        /**
         * @var bool
         */
        private $showViewedVideo = true;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $user;

        /**
         * @param int $userSettingsId
         */
        public function setUserSettingsId($userSettingsId)
        {
            $this->userSettingsId = $userSettingsId;
        }

        /**
         * @return int
         */
        public function getUserSettingsId()
        {
            return $this->userSettingsId;
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
         * @param boolean $showUserPage
         */
        public function setShowUserPage($showUserPage)
        {
            $this->showUserPage = $showUserPage;
        }

        /**
         * @return boolean
         */
        public function getShowUserPage()
        {
            return $this->showUserPage;
        }

        /**
         * @param boolean $showUserRating
         */
        public function setShowUserRating($showUserRating)
        {
            $this->showUserRating = $showUserRating;
        }

        /**
         * @return boolean
         */
        public function getShowUserRating()
        {
            return $this->showUserRating;
        }

        /**
         * @param boolean $showViewedVideo
         */
        public function setShowViewedVideo($showViewedVideo)
        {
            $this->showViewedVideo = $showViewedVideo;
        }

        /**
         * @return boolean
         */
        public function getShowViewedVideo()
        {
            return $this->showViewedVideo;
        }
    }