<?
    namespace Znaika\FrontendBundle\Entity\User;

    use Doctrine\ORM\EntityRepository;

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