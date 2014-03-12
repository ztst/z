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

        public function __construct()
        {
            $this->users   = new \Doctrine\Common\Collections\ArrayCollection();
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
    }