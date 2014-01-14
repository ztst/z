<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;

    class UserRegistrationRedisRepository implements IUserRegistrationRepository
    {
        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function save(UserRegistration $userRegistration)
        {
            return true;
        }

        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function delete(UserRegistration $userRegistration)
        {
            return true;
        }

        /**
         * @param $key
         *
         * @return UserRegistration|null
         */
        public function getOneByRegisterKey($key)
        {
            return null;
        }
    }