<?
    namespace Znaika\FrontendBundle\Repository\Profile\Badge;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\Badge\BaseUserBadge;

    class UserBadgeDBRepository extends EntityRepository implements IUserBadgeRepository
    {
        /**
         * @param BaseUserBadge $badge
         *
         * @return bool
         */
        public function save(BaseUserBadge $badge)
        {
            $this->getEntityManager()->persist($badge);
            $this->getEntityManager()->flush();
        }
    }