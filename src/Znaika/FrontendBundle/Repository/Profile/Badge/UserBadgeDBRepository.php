<?
    namespace Znaika\FrontendBundle\Repository\Profile\Badge;

    use Doctrine\ORM\EntityRepository;
    use Znaika\FrontendBundle\Entity\Profile\Badge\BaseUserBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

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

        /**
         * @param User $user
         *
         * @return BaseUserBadge[]
         */
        public function getUserNotViewedBadges(User $user)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('ub')
               ->from('ZnaikaFrontendBundle:Profile\Badge\BaseUserBadge', 'ub')
               ->andWhere('ub.user = :user_id')
               ->setParameter('user_id', $user->getUserId())
               ->andWhere('ub.isViewed = :is_viewed')
               ->setParameter('is_viewed', false, \PDO::PARAM_BOOL)
               ->orderBy('ub.createdTime', 'DESC');

            return $qb->getQuery()->getResult();
        }
    }