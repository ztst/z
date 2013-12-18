<?
    namespace Znaika\FrontendBundle\Repository\User;

    use Znaika\FrontendBundle\Entity\User\UserInfo;

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