<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\UserRegistration;

    class UserRegistrationDBRepository extends EntityRepository implements IUserRegistrationRepository
    {
        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function save(UserRegistration $userRegistration)
        {
            $this->getEntityManager()->persist($userRegistration);
            $this->getEntityManager()->flush();
        }

        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function delete(UserRegistration $userRegistration)
        {
            $this->getEntityManager()->remove($userRegistration);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $key
         *
         * @return UserRegistration|null
         */
        public function getOneByRegisterKey($key)
        {
            return $this->findOneByRegisterKey($key);
        }
    }