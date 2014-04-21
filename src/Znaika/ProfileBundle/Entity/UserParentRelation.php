<?php

    namespace Znaika\ProfileBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;

    class UserParentRelation
    {
        /**
         * @var integer
         */
        private $userParentRelationId;

        /**
         * @var bool
         */
        private $approved = false;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $child;

        /**
         * @var \Znaika\ProfileBundle\Entity\User
         */
        private $parent;

        /**
         * @param int $userParentRelationId
         */
        public function setUserParentRelationId($userParentRelationId)
        {
            $this->userParentRelationId = $userParentRelationId;
        }

        /**
         * @return int
         */
        public function getUserParentRelationId()
        {
            return $this->userParentRelationId;
        }

        /**
         * @param boolean $approved
         */
        public function setApproved($approved)
        {
            $this->approved = $approved;
        }

        /**
         * @return boolean
         */
        public function getApproved()
        {
            return $this->approved;
        }

        /**
         * @param \Znaika\ProfileBundle\Entity\User $child
         */
        public function setChild($child)
        {
            $this->child = $child;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getChild()
        {
            return $this->child;
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
         * @param \Znaika\ProfileBundle\Entity\User $parent
         */
        public function setParent($parent)
        {
            $this->parent = $parent;
        }

        /**
         * @return \Znaika\ProfileBundle\Entity\User
         */
        public function getParent()
        {
            return $this->parent;
        }
    }