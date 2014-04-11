<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\User;

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
         * @param $userIds
         *
         * @return User[]
         */
        public function getByUserIds($userIds)
        {
            return null;
        }

        /**
         * @param $vkId
         *
         * @return User
         */
        public function getOneByVkId($vkId)
        {
            return null;
        }

        /**
         * @param $facebookId
         *
         * @return User
         */
        public function getOneByFacebookId($facebookId)
        {
            return null;
        }

        /**
         * @param $odnoklassnikiId
         *
         * @return User
         */
        public function getOneByOdnoklassnikiId($odnoklassnikiId)
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

        /**
         * @param integer $limit
         *
         * @return User[]
         */
        public function getUsersTopByPoints($limit)
        {
            return null;
        }

        public function getNotVerifiedUsers($userRoles = array())
        {
            return null;
        }

        public function countNotVerifiedUsers($userRoles = array())
        {
            return null;
        }
    }