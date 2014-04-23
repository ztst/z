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
        private $approvedByChild = false;

        /**
         * @var bool
         */
        private $approvedByParent = false;

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
        public function setApprovedByChild($approved)
        {
            $this->approvedByChild = $approved;
        }

        /**
         * @return boolean
         */
        public function getApprovedByChild()
        {
            return $this->approvedByChild;
        }

        /**
         * @param boolean $approvedByParent
         */
        public function setApprovedByParent($approvedByParent)
        {
            $this->approvedByParent = $approvedByParent;
        }

        /**
         * @return boolean
         */
        public function getApprovedByParent()
        {
            return $this->approvedByParent;
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