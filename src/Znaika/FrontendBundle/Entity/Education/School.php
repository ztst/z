<?php

    namespace Znaika\FrontendBundle\Entity\Education;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * School
     */
    class School
    {
        /**
         * @var integer
         */
        protected $schoolId;

        /**
         * @var string
         */
        protected $name;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $users;

        /**
         * @var \Znaika\FrontendBundle\Entity\Location\City
         */
        protected $city;

         /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $classrooms;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->users = new \Doctrine\Common\Collections\ArrayCollection();
			$this->$classrooms = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get schoolId
         *
         * @return integer
         */
        public function getSchoolId()
        {
            return $this->schoolId;
        }

        /**
         * Set name
         *
         * @param string $name
         *
         * @return School
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name
         *
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Add users
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $users
         *
         * @return School
         */
        public function addUser(\Znaika\FrontendBundle\Entity\Profile\User $users)
        {
            $this->users[] = $users;

            return $this;
        }

        /**
         * Remove users
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\User $users
         */
        public function removeUser(\Znaika\FrontendBundle\Entity\Profile\User $users)
        {
            $this->users->removeElement($users);
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
         * Set city
         *
         * @param \Znaika\FrontendBundle\Entity\Location\City $city
         *
         * @return User
         */
        public function setCity(\Znaika\FrontendBundle\Entity\Location\City $city = null)
        {
            $this->city = $city;

            return $this;
        }

        /**
         * Get city
         *
         * @return \Znaika\FrontendBundle\Entity\Location\City
         */
        public function getCity()
        {
            return $this->city;
        }
		/**
         * Add classrooms
         *
         * @param \Znaika\FrontendBundle\Entity\Education\Classroom $classrooms
         *
         * @return School
         */
        public function addClassroom(\Znaika\FrontendBundle\Entity\Education\Classroom $classrooms)
        {
            $this->classrooms[] = $classrooms;

            return $this;
        }

        /**
         * Remove classrooms
         *
         * @param \Znaika\FrontendBundle\Entity\Education\Classroom $classrooms
         */
        public function removeClassroom(\Znaika\FrontendBundle\Entity\Education\Classroom $classrooms)
        {
            $this->classrooms->removeElement($classrooms);
        }

        /**
         * Get classrooms
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getClassrooms()
        {
            return $this->classrooms;
        }
    }