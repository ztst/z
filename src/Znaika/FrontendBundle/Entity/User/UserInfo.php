<?
    namespace Znaika\FrontendBundle\Entity\User;

    use Symfony\Component\Security\Core\Role\Role;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Doctrine\ORM\Mapping as ORM;

    class UserInfo implements UserInterface
    {
        /**
         * @var integer
         */
        private $userInfoId;

        /**
         * @var string
         */
        private $firstName;

        /**
         * @var string
         */
        private $lastName;

        /**
         * @var string
         */
        private $email;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var string
         */
        private $password;

        /**
         * Get userInfoId
         *
         * @return integer
         */
        public function getUserInfoId()
        {
            return $this->userInfoId;
        }

        /**
         * Set firstName
         *
         * @param string $firstName
         *
         * @return UserInfo
         */
        public function setFirstName($firstName)
        {
            $this->firstName = $firstName;

            return $this;
        }

        /**
         * Get firstName
         *
         * @return string
         */
        public function getFirstName()
        {
            return $this->firstName;
        }

        /**
         * Set lastName
         *
         * @param string $lastName
         *
         * @return UserInfo
         */
        public function setLastName($lastName)
        {
            $this->lastName = $lastName;

            return $this;
        }

        /**
         * Get lastName
         *
         * @return string
         */
        public function getLastName()
        {
            return $this->lastName;
        }

        /**
         * Set email
         *
         * @param string $email
         *
         * @return UserInfo
         */
        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get email
         *
         * @return string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return UserInfo
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
         * @inheritDoc
         */
        public function getRoles()
        {
            return array('ROLE_USER');
        }

        /**
         * @inheritDoc
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * Set password
         *
         * @param string $password
         *
         * @return UserInfo
         */
        public function setPassword($password)
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function getSalt()
        {
            return null;
        }

        /**
         * @inheritDoc
         */
        public function getUsername()
        {
            return $this->email;
        }


        /**
         * @inheritDoc
         */
        public function eraseCredentials()
        {
        }

    }