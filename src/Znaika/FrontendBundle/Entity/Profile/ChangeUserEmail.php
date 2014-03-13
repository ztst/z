<?php

    namespace Znaika\FrontendBundle\Entity\Profile;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
    use Symfony\Component\Validator\Constraints as Assert;

    class ChangeUserEmail
    {
        const EXPIRED_TIME = "P3D";

        /**
         * @SecurityAssert\UserPassword(
         *     message = "Введен неверный пароль"
         * )
         */
        private $oldPassword;

        /**
         * @var integer
         */
        private $changeUserEmailId;

        /**
         * @var string
         */
        private $changeKey;

        /**
         * @var \DateTime
         */
        private $createdTime;

        /**
         * @var string
         */
        private $newEmail;

        /**
         * @var \Znaika\FrontendBundle\Entity\Profile\User
         */
        private $user;

        /**
         * @param string $changeKey
         */
        public function setChangeKey($changeKey)
        {
            $this->changeKey = $changeKey;
        }

        /**
         * @return string
         */
        public function getChangeKey()
        {
            return $this->changeKey;
        }

        /**
         * @param int $changeUserEmailId
         */
        public function setChangeUserEmailId($changeUserEmailId)
        {
            $this->changeUserEmailId = $changeUserEmailId;
        }

        /**
         * @return int
         */
        public function getChangeUserEmailId()
        {
            return $this->changeUserEmailId;
        }

        /**
         * @param \DateTime $createdTime
         */
        public function setCreatedTime($createdTime)
        {
            $this->createdTime = $createdTime;
        }

        /**
         * @return \DateTime
         */
        public function getCreatedTime()
        {
            return $this->createdTime;
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Profile\User $user
         */
        public function setUser($user)
        {
            $this->user = $user;
        }

        /**
         * @return \Znaika\FrontendBundle\Entity\Profile\User
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param string $newEmail
         */
        public function setNewEmail($newEmail)
        {
            $this->newEmail = $newEmail;
        }

        /**
         * @return string
         */
        public function getNewEmail()
        {
            return $this->newEmail;
        }

        /**
         * @param mixed $oldPassword
         */
        public function setOldPassword($oldPassword)
        {
            $this->oldPassword = $oldPassword;
        }

        /**
         * @return mixed
         */
        public function getOldPassword()
        {
            return $this->oldPassword;
        }
    }