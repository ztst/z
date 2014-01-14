<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;

    interface IUserRegistrationRepository
    {
        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function save(UserRegistration $userRegistration);

        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function delete(UserRegistration $userRegistration);

        /**
         * @param $key
         *
         * @return UserRegistration|null
         */
        public function getOneByRegisterKey($key);
    }