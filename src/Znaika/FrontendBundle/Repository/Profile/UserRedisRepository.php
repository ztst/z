<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserRedisRepository implements IUserRepository
    {
        /**
         * @param User $user
         *
         * @return bool
         */
        public function save(User $user)
        {
            return true;
        }

        /**
         * @param $userId
         *
         * @return User|null
         */
        public function getOneByUserId($userId)
        {
            return null;
        }

        /**
         * @param string $searchString
         *
         * @return User[]|null
         */
        public function getUsersBySearchString($searchString)
        {
            return null;
        }

        /**
         * @param string $email
         *
         * @return User|null
         */
        public function getOneByEmail($email)
        {
            return null;
        }
    }