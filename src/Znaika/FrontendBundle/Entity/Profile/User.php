<?
    namespace Znaika\FrontendBundle\Entity\Profile;

    use Symfony\Component\Security\Core\Role\Role;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Doctrine\ORM\Mapping as ORM;

    class User implements UserInterface
    {
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
         * @var \Doctrine\Common\Collections\Collection
         */
        private $videoComments;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->videoComments = new \Doctrine\Common\Collections\ArrayCollection();
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
    }