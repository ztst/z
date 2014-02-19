<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\PasswordRecovery;

    class PasswordRecoveryDBRepository extends EntityRepository implements IPasswordRecoveryRepository
    {
        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function save(PasswordRecovery $passwordRecovery)
        {
            $this->getEntityManager()->persist($passwordRecovery);
            $this->getEntityManager()->flush();
        }

        /**
         * @param PasswordRecovery $passwordRecovery
         *
         * @return bool
         */
        public function delete(PasswordRecovery $passwordRecovery)
        {
            $this->getEntityManager()->remove($passwordRecovery);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $key
         *
         * @return PasswordRecovery|null
         */
        public function getOneByPasswordRecoveryKey($key)
        {
            return $this->getOneByPasswordRecoveryKey($key);
        }
    }