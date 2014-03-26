<?
    namespace Znaika\FrontendBundle\Repository\Profile\Ban;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\Ban\Info;

    class InfoDBRepository extends EntityRepository implements IInfoRepository
    {
        public function save(Info $info)
        {
            $this->getEntityManager()->persist($info);
            $this->getEntityManager()->flush();
        }
    }
