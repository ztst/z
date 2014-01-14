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
            $this->_em->persist($userRegistration);
            $this->_em->flush();
        }

        /**
         * @param UserRegistration $userRegistration
         *
         * @return bool
         */
        public function delete(UserRegistration $userRegistration)
        {
            $this->_em->remove($userRegistration);
            $this->_em->flush();
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