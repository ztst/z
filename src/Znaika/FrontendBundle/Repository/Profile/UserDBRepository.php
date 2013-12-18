<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class UserDBRepository extends EntityRepository implements IUserRepository
    {
        /**
         * @param User $user
         *
         * @return bool
         */
        public function save(User $user)
        {
            $this->_em->persist($user);
            $this->_em->flush();
        }

        /**
         * @param $userId
         *
         * @return User|null
         */
        public function getOneByUserId($userId)
        {
            return $this->findOneByUserId($userId);
        }
    }