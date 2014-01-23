<?php

    namespace Znaika\FrontendBundle\Entity\Education;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * Classroom
     */
    class Classroom
    {
        /**
         * @var integer
         */
        private $classroomId;

        /**
         * @var integer
         */
        private $grade;

        /**
         * @var string
         */
        private $letter;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $users;

        /**
         * @var \Znaika\FrontendBundle\Entity\Education\School
         */
        private $school;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get classroomId
         *
         * @return integer;
         */
        public function getClassroomId()
        {
            return $this->classroomId;
        }

        /**
         * Set grade
         *
         * @param integer $grade
         *
         * @return Classroom
         */
        public function setGrade($grade)
        {
            $this->grade = $grade;

            return $this;
        }

        /**
         * Get grade
         *
         * @return integer
         */
        public function getGrade()
        {
            return $this->grade;
        }

        /**
         * Set letter
         *
         * @param string $letter
         *
         * @return Classroom
         */
        public function setLetter($letter)
        {
            $this->letter = $letter;

            return $this;
        }

        /**
         * Get letter
         *
         * @return string
         */
        public function getLetter()
        {
            return $this->letter;
        }

        /**
         * Add users
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         *
         * @return Classroom
         */
        public function addUser(\Znaika\FrontendBundle\Entity\Profile\User $user)
        {
            $this->users[] = $user;

            return $this;
        }

        /**
         * Remove users
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         */
        public function removeUser(\Znaika\FrontendBundle\Entity\Profile\User $user)
        {
            $this->users->removeElement($user);
        }

        /**
         * Get users
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getUsers()
        {
            return $this->users;
        }

        /**
         * Set school
         *
         * @param \Znaika\FrontendBundle\Entity\Education\School $school
         *
         * @return Classroom
         */
        public function setSchool(\Znaika\FrontendBundle\Entity\Education\School $school = null)
        {
            $this->school = $school;

            return $this;
        }

        /**
         * @return string
         */
        public function __toString()
        {
            return $this->grade . $this->letter;
        }

        /**
         * Get school
         *
         * @return \Znaika\FrontendBundle\Entity\Education\School $school
         */
        public function getSchool()
        {
            return $this->school;
        }
    }