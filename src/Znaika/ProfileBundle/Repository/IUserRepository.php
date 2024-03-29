<?
    namespace Znaika\ProfileBundle\Repository;

    use Znaika\ProfileBundle\Entity\User;

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

        /**
         * @param $userIds
         *
         * @return User[]
         */
        public function getByUserIds($userIds);

        /**
         * @param $vkId
         *
         * @return User
         */
        public function getOneByVkId($vkId);

        /**
         * @param $facebookId
         *
         * @return User
         */
        public function getOneByFacebookId($facebookId);

        /**
         * @param $odnoklassnikiId
         *
         * @return User
         */
        public function getOneByOdnoklassnikiId($odnoklassnikiId);

        /**
         * @param string $searchString
         *
         * @return User[]|null
         */
        public function getUsersBySearchString($searchString);

        /**
         * @param string $email
         *
         * @return User|null
         */
        public function getOneByEmail($email);

        /**
         * @param integer $limit
         *
         * @return User[]
         */
        public function getUsersTopByPoints($limit);

        /**
         * @param $userRoles
         *
         * @return User[]
         */
        public function getNotVerifiedUsers($userRoles = array());

        /**
         * @param $userRoles
         *
         * @return int
         */
        public function countNotVerifiedUsers($userRoles = array());
    }