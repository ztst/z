<?
    namespace Znaika\FrontendBundle\Repository\Profile;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\ChangeUserEmail;

    class ChangeUserEmailDBRepository extends EntityRepository implements IChangeUserEmailRepository
    {
        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function save(ChangeUserEmail $changeUserEmail)
        {
            $this->getEntityManager()->persist($changeUserEmail);
            $this->getEntityManager()->flush();
        }

        /**
         * @param ChangeUserEmail $changeUserEmail
         *
         * @return bool
         */
        public function delete(ChangeUserEmail $changeUserEmail)
        {
            $this->getEntityManager()->remove($changeUserEmail);
            $this->getEntityManager()->flush();
        }

        /**
         * @param $key
         *
         * @return ChangeUserEmail
         */
        public function getOneByChangeKey($key)
        {
            return $this->findOneByChangeKey($key);
        }
    }