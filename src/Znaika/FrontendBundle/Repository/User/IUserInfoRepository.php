<?
    namespace Znaika\FrontendBundle\Repository\User;

    use Znaika\FrontendBundle\Entity\User\UserInfo;

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