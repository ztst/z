<?
    namespace Znaika\FrontendBundle\Entity\Profile;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\User\AdvancedUserInterface;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserStatus;
    use FOS\MessageBundle\Model\ParticipantInterface;

    class User implements AdvancedUserInterface, ParticipantInterface, \Serializable
    {
        const SALT = "iHc26#r8AQ6@Vyo6^23!hMm";

        /**
         * @var integer
         */
        private $userId;
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
         * @var integer
         */
        private $status;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoComments;

        /**
         * @var \Doctrine\Common\Collections\Collection
         */
        private $userRegistrations;

        /**
         * @var \Znaika\FrontendBundle\Entity\Location\City
         */
        private $city;

        /**
         * @var integer
         */
        private $sex = 0;

        /**
         * @var integer
         */
        private $points = 0;

        /**
         * @var \Znaika\FrontendBundle\Entity\Education\School
         */
        private $school;

        /**
         * @var \Znaika\FrontendBundle\Entity\Education\Classroom
         */
        private $classroom;

        /**
         * @var \DateTime
         */
        private $birthDate;

        /**
         * @var integer
         */
        private $role = 0;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videoComments     = new \Doctrine\Common\Collections\ArrayCollection();
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
            return array(UserRole::getSecurityTextByRole($this->getRole()));
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
         * Set school
         *
         * @param \Znaika\FrontendBundle\Entity\Education\School $school
         *
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

        /**
         * Set sex
         *
         * @param integer $sex
         *
         * @return User
         */
        public function setSex($sex)
        {
            $this->sex = $sex;

            return $this;
        }

        /**
         * Get sex
         *
         * @return integer
         */
        public function getSex()
        {
            return $this->sex;
        }

        /**
         * Gets the unique identifier of the participant
         *
         * @return integer
         */
        public function getId()
        {
            return $this->getUserId();
        }

        public function __toString()
        {
            return (string)$this->getFirstName() . "  " . $this->getLastName();
        }

        /**
         * @param int $points
         */
        public function setPoints($points)
        {
            $this->points = $points;
        }

        /**
         * @return int
         */
        public function getPoints()
        {
            return $this->points;
        }

        /**
         * Set classroom
         *
         * @param \Znaika\FrontendBundle\Entity\Education\Classroom $classroom
         *
         * @return User
         */
        public function setClassroom(\Znaika\FrontendBundle\Entity\Education\Classroom $classroom = null)
        {
            $this->classroom = $classroom;

            return $this;
        }

        /**
         * Get classroom
         *
         * @return \Znaika\FrontendBundle\Entity\Education\Classroom
         */
        public function getClassroom()
        {
            return $this->classroom;
        }

        /**
         * @param \DateTime $birthDate
         */
        public function setBirthDate($birthDate)
        {
            $this->birthDate = $birthDate;
        }

        /**
         * @return \DateTime
         */
        public function getBirthDate()
        {
            return $this->birthDate;
        }

        /**
         * @param int $role
         */
        public function setRole($role)
        {
            $this->role = $role;
        }

        /**
         * @return int
         */
        public function getRole()
        {
            return $this->role;
        }

        public function serialize()
        {
            return serialize(array(
                $this->userId,
                $this->password,
                $this->firstName,
                $this->lastName,
                $this->createdTime,
            ));
        }

        public function unserialize($serialized)
        {
            list(
                $this->userId,
                $this->password,
                $this->firstName,
                $this->lastName,
                $this->createdTime,
                ) = unserialize($serialized);
        }
    }