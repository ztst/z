<?
    namespace Znaika\ProfileBundle\Repository\Ban;

    use Doctrine\ORM\EntityRepository;
    use Znaika\ProfileBundle\Entity\Ban\Info;

    class InfoDBRepository extends EntityRepository implements IInfoRepository
    {
        public function save(Info $info)
        {
            $this->getEntityManager()->persist($info);
            $this->getEntityManager()->flush();
        }
    }
