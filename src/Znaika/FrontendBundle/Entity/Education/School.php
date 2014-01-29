<?

    namespace Znaika\FrontendBundle\Entity\Education;

    use Doctrine\ORM\Mapping as ORM;

    class School
    {
        /**
         * @var integer
         */
        private $schoolId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $users;

        /**
         * @var \Znaika\FrontendBundle\Entity\Location\City
         */
        private $city;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $classrooms;

        public function __construct()
        {
            $this->users       = new \Doctrine\Common\Collections\ArrayCollection();
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
         * Add user
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
         * Remove user
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
         * @return School
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
         * @param \Znaika\FrontendBundle\Entity\Education\Classroom $classroom
         *
         * @return School
         */
        public function addClassroom(\Znaika\FrontendBundle\Entity\Education\Classroom $classroom)
        {
            $this->classrooms[] = $classroom;

            return $this;
        }

        /**
         * Remove classrooms
         *
         * @param \Znaika\FrontendBundle\Entity\Education\Classroom $classroom
         */
        public function removeClassroom(\Znaika\FrontendBundle\Entity\Education\Classroom $classroom)
        {
            $this->classrooms->removeElement($classroom);
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