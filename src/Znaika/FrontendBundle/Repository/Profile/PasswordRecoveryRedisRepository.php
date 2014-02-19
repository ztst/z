<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;

    class PasswordRecoveryRedisRepository implements IPasswordRecoveryRepository
    {
        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function save(PasswordRecovery $passwordRecovery)
        {
            return true;
        }

        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function delete(PasswordRecovery $passwordRecovery)
        {
            return true;
        }

        /**
         * @param $key
         *
         * @return PasswordRecovery|null
         */
        public function getOneByPasswordRecoveryKey($key)
        {
            return null;
        }
    }