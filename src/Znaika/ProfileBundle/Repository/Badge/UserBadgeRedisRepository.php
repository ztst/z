<?
    namespace Znaika\ProfileBundle\Repository\Badge;

    use Znaika\ProfileBundle\Entity\Badge\BaseUserBadge;
    use Znaika\ProfileBundle\Entity\User;

    class UserBadgeRedisRepository implements IUserBadgeRepository
    {
        /**
         * @param BaseUserBadge $badge
         *
         * @return bool
         */
        public function save(BaseUserBadge $badge)
        {
            return true;
        }

        /**
         * @param User $user
         *
         * @return BaseUserBadge[]
         */
        public function getUserNotViewedBadges(User $user)
        {
            return null;
        }

        /**
         * @param integer $limit
         *
         * @return BaseUserBadge[]
         */
        public function getNewestBadges($limit)
        {
            return null;
        }
    }