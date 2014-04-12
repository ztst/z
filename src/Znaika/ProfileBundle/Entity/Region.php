<?php

    namespace Znaika\ProfileBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;

    class Region
    {
        /**
         * @var integer
         */
        private $regionId;

        /**
         * @var String
         */
        private $regionName;

        /**
         * @var ArrayCollection
         */
        private $users;

        /**
         * @return int
         */
        public function getRegionId()
        {
            return $this->regionId;
        }

        /**
         * @param int $regionId
         */
        public function setRegionId($regionId)
        {
            $this->regionId = $regionId;
        }

        /**
         * @return String
         */
        public function getRegionName()
        {
            return $this->regionName;
        }

        /**
         * @param String $regionName
         */
        public function setRegionName($regionName)
        {
            $this->regionName = $regionName;
        }

        /**
         * @return \Doctrine\Common\Collections\ArrayCollection
         */
        public function getUsers()
        {
            return $this->users;
        }

        /**
         * @param $users
         *
         * @return Region
         */
        public function setUsers($users)
        {
            $this->users = $users;

            return $this;
        }

        /**
         * @param User $user
         */
        public function addUser(User $user)
        {
            $this->users->add($user);
        }

        /**
         * @param User $user
         */
        public function deleteUser(User $user)
        {
            $this->users->removeElement($user);
        }
    }