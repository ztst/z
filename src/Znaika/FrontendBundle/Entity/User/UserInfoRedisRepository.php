<?
    namespace Znaika\FrontendBundle\Entity\User;

    class UserInfoRedisRepository implements IUserInfoRepository
    {
        /**
         * @param UserInfo $userInfo
         *
         * @return bool
         */
        public function save(UserInfo $userInfo)
        {
            return true;
        }

        /**
         * @param $userId
         *
         * @return UserInfo|null
         */
        public function getOneByUserId($userId)
        {
            return null;
        }

    }