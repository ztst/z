<?
    namespace Znaika\ProfileBundle\Repository\Badge;

    use Doctrine\ORM\EntityRepository;
    use Znaika\ProfileBundle\Entity\Badge\BaseUserBadge;
    use Znaika\ProfileBundle\Entity\User;

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
               ->from('ZnaikaProfileBundle:Badge\BaseUserBadge', 'ub')
               ->andWhere('ub.user = :user_id')
               ->setParameter('user_id', $user->getUserId())
               ->andWhere('ub.isViewed = :is_viewed')
               ->setParameter('is_viewed', false, \PDO::PARAM_BOOL)
               ->orderBy('ub.createdTime', 'DESC');

            return $qb->getQuery()->getResult();
        }

        /**
         * @param integer $limit
         *
         * @return BaseUserBadge[]
         */
        public function getNewestBadges($limit)
        {
            $qb = $this->getEntityManager()
                       ->createQueryBuilder();

            $qb->select('ub')
               ->from('ZnaikaProfileBundle:Badge\BaseUserBadge', 'ub')
               ->orderBy('ub.createdTime', 'DESC')
               ->setMaxResults($limit);

            return $qb->getQuery()->getResult();
        }
    }