<?

    namespace Znaika\FrontendBundle\Entity\Location;

    use Doctrine\ORM\Mapping as ORM;

    class City
    {
        /**
         * @var integer
         */
        private $cityId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $users;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $schools;

        public function __construct()
        {
            $this->users   = new \Doctrine\Common\Collections\ArrayCollection();
            $this->schools = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get cityId
         *
         * @return integer
         */
        public function getCityId()
        {
            return $this->cityId;
        }

        /**
         * Set name
         *
         * @param string $name
         *
         * @return City
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
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         *
         * @return City
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
         * Add schools
         *
         * @param \Znaika\FrontendBundle\Entity\Education\School $school
         *
         * @return City
         */
        public function addSchool(\Znaika\FrontendBundle\Entity\Education\School $school)
        {
            $this->schools[] = $school;

            return $this;
        }

        /**
         * Remove schools
         *
         * @param \Znaika\FrontendBundle\Entity\Education\School $school
         */
        public function removeSchool(\Znaika\FrontendBundle\Entity\Education\School $school)
        {
            $this->schools->removeElement($school);
        }

        /**
         * Get schools
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getSchools()
        {
            return $this->schools;
        }
    }