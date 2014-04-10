<?
    namespace Znaika\ProfileBundle\Form\Model;

    use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
    use Symfony\Component\Validator\Constraints as Assert;

    class ChangePassword
    {
        /**
         * @SecurityAssert\UserPassword(
         *     message = "Введен неверный пароль"
         * )
         */
        private $oldPassword;

        private $newPassword;

        /**
         * @param mixed $newPassword
         */
        public function setNewPassword($newPassword)
        {
            $this->newPassword = $newPassword;
        }

        /**
         * @return mixed
         */
        public function getNewPassword()
        {
            return $this->newPassword;
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