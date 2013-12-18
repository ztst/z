<?
    namespace Znaika\FrontendBundle\Entity\User;

    interface IUserInfoRepository
    {
        /**
         * @param UserInfo $userInfo
         *
         * @return bool
         */
        public function save(UserInfo $userInfo);

        /**
         * @param $userId
         *
         * @return UserInfo|null
         */
        public function getOneByUserId($userId);
    }