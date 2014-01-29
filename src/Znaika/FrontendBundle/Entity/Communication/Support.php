<?
    namespace Znaika\FrontendBundle\Entity\Communication;

    use Doctrine\ORM\Mapping as ORM;

    class Support
    {
        /**
         * @var integer
         */
        private $supportId;

        /**
         * @var string
         */
        private $name;

        /**
         * @var string
         */
        private $email;

        /**
         * @var string
         */
        private $text;

        /**
         * @var integer
         */
        private $status;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * Get supportId
         *
         * @return integer
         */
        public function getSupportId()
        {
            return $this->supportId;
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param string $email
         */
        public function setEmail($email)
        {
            $this->email = $email;
        }

        /**
         * @return string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set text
         *
         * @param string $text
         *
         * @return Support
         */
        public function setText($text)
        {
            $this->text = $text;

            return $this;
        }

        /**
         * Get text
         *
         * @return string
         */
        public function getText()
        {
            return $this->text;
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
         * Set createdTime
         *
         * @param \DateTime $createdTime
         *
         * @return Support
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
    }