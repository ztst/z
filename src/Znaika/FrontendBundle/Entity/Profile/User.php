<?
    namespace Znaika\FrontendBundle\Entity\Profile;

    use Symfony\Component\Security\Core\User\AdvancedUserInterface;
    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;

    /**
     * Class User
     * @package Znaika\FrontendBundle\Entity\Profile
     */
    class User implements AdvancedUserInterface
    {
        const SALT = "iHc26#r8AQ6@Vyo6^23!hMm";

        /**
         * @var integer
         */
        protected $userId;
        /**
         * @var string
         */
        protected $firstName;

        /**
         * @var string
         */
        protected $lastName;

        /**
         * @var string
         */
        protected $email;

        /**
         * @var \DateTime
         */
        protected $createdTime;

        /**
         * @var string
         */
        protected $password;

        /**
         * @var integer
         */
        protected $status;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $videoComments;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        protected $userRegistrations;

        /**
         * @var \Znaika\FrontendBundle\Entity\Location\City
         */
        private $city;

        /**
         * @var \Znaika\FrontendBundle\Entity\Education\School
         */
        private $school;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videoComments = new \Doctrine\Common\Collections\ArrayCollection();
            $this->userRegistrations = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Get userId
         *
         * @return integer
         */
        public function getUserId()
        {
            return $this->userId;
        }

        /**
         * Set firstName
         *
         * @param string $firstName
         *
         * @return User
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
         * @return User
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
         * @return User
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
         * @return User
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
         * @return User
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
            return $this->getCreatedTime()->getTimestamp() . User::SALT;
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

        /**
         * Add videoComments
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         *
         * @return User
         */
        public function addVideoComment(\Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment)
        {
            $videoComment->setUser($this);
            $this->videoComments[] = $videoComment;

            return $this;
        }

        /**
         * Remove videoComments
         *
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment
         */
        public function removeVideoComment(\Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $videoComment)
        {
            $this->videoComments->removeElement($videoComment);
        }

        /**
         * Get videoComments
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getVideoComments()
        {
            return $this->videoComments;
        }

        /**
         * @param int $status
         */
        public function setStatus($status)
        {
            $this->status = $status;
        }

        /**
         * @return int
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * Checks whether the user's account has expired.
         *
         * Internally, if this method returns false, the authentication system
         * will throw an AccountExpiredException and prevent login.
         *
         * @return Boolean true if the user's account is non expired, false otherwise
         *
         * @see AccountExpiredException
         */
        public function isAccountNonExpired()
        {
            return true;
        }

        /**
         * Checks whether the user is locked.
         *
         * Internally, if this method returns false, the authentication system
         * will throw a LockedException and prevent login.
         *
         * @return Boolean true if the user is not locked, false otherwise
         *
         * @see LockedException
         */
        public function isAccountNonLocked()
        {
            return true;
        }

        /**
         * Checks whether the user's credentials (password) has expired.
         *
         * Internally, if this method returns false, the authentication system
         * will throw a CredentialsExpiredException and prevent login.
         *
         * @return Boolean true if the user's credentials are non expired, false otherwise
         *
         * @see CredentialsExpiredException
         */
        public function isCredentialsNonExpired()
        {
            return true;
        }

        /**
         * Checks whether the user is enabled.
         *
         * Internally, if this method returns false, the authentication system
         * will throw a DisabledException and prevent login.
         *
         * @return Boolean true if the user is enabled, false otherwise
         *
         * @see DisabledException
         */
        public function isEnabled()
        {
            return $this->status == UserStatus::ACTIVE;
        }

        /**
         * Add userRegistrations
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\UserRegistration $userRegistrations
         *
         * @return User
         */
        public function addUserRegistration(\Znaika\FrontendBundle\Entity\Profile\UserRegistration $userRegistrations)
        {
            $this->userRegistrations[] = $userRegistrations;

            return $this;
        }

        /**
         * Remove userRegistrations
         *
         * @param \Znaika\FrontendBundle\Entity\Profile\UserRegistration $userRegistrations
         */
        public function removeUserRegistration(\Znaika\FrontendBundle\Entity\Profile\UserRegistration $userRegistrations)
        {
            $this->userRegistrations->removeElement($userRegistrations);
        }

        /**
         * Get userRegistrations
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getUserRegistrations()
        {
            return $this->userRegistrations;
        }

        /**
         * Returns last user registration or new user registration object
         *
         * @return UserRegistration
         */
        public function getLastUserRegistration()
        {
            if ($this->userRegistrations->count())
            {
                $userRegistration = $this->userRegistrations->last();
            }
            else
            {
                $userRegistration = new UserRegistration();
                $userRegistration->setUser($this);
            }

            return $userRegistration;
        }

        /**
         * Set city
         *
         * @param \Znaika\FrontendBundle\Entity\Location\City $city
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
         * Set school
         *
         * @param \Znaika\FrontendBundle\Entity\Education\School $school
         * @return User
         */
        public function setSchool(\Znaika\FrontendBundle\Entity\Education\School $school = null)
        {
            $this->school = $school;

            return $this;
        }

        /**
         * Get school
         *
         * @return \Znaika\FrontendBundle\Entity\Education\School
         */
        public function getSchool()
        {
            return $this->school;
        }
    }