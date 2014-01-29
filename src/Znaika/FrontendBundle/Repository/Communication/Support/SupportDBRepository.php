<?
    namespace Znaika\FrontendBundle\Repository\Communication\Support;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Communication\Support;

    class SupportDBRepository extends EntityRepository implements ISupportRepository
    {
        /**
         * @param Support $support
         *
         * @return bool
         */
        public function save(Support $support)
        {
            $this->getEntityManager()->persist($support);
            $this->getEntityManager()->flush();
        }
    }