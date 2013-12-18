<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;

    interface IUserRepository
    {
        /**
         * @param User $user
         *
         * @return bool
         */
        public function save(User $user);

        /**
         * @param $userId
         *
         * @return User|null
         */
        public function getOneByUserId($userId);
    }