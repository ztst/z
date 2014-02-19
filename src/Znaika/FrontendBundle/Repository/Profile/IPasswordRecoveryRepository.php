<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;

    interface IPasswordRecoveryRepository
    {
        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function save(PasswordRecovery $passwordRecovery);

        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function delete(PasswordRecovery $passwordRecovery);

        /**
         * @param $key
         *
         * @return PasswordRecovery|null
         */
        public function getOneByPasswordRecoveryKey($key);
    }