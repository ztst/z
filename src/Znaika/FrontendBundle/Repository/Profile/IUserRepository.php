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
    }