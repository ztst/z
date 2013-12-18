<?
    namespace Znaika\FrontendBundle\Repository\User;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\User\UserInfo;

    class UserInfoDBRepository extends EntityRepository implements IUserInfoRepository
    {
        /**
         * @param UserInfo $userInfo
         *
         * @return bool
         */
        public function save(UserInfo $userInfo)
        {
            $this->_em->persist($userInfo);
            $this->_em->flush();
        }

        /**
         * @param $userId
         *
         * @return UserInfo|null
         */
        public function getOneByUserId($userId)
        {
            return $this->findOneByUserInfoId($userId);
        }
    }