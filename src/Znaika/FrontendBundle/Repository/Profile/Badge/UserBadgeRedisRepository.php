<?
    namespace Znaika\FrontendBundle\Repository\Profile\Badge;

    use Znaika\FrontendBundle\Entity\Profile\Badge\BaseUserBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

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
    }