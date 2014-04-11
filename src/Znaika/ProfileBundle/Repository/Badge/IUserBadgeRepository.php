<?
    namespace Znaika\ProfileBundle\Repository\Badge;

    use Znaika\ProfileBundle\Entity\Badge\BaseUserBadge;
    use Znaika\ProfileBundle\Entity\User;

    interface IUserBadgeRepository
    {
        /**
         * @param BaseUserBadge $badge
         *
         * @return bool
         */
        public function save(BaseUserBadge $badge);

        /**
         * @param User $user
         *
         * @return BaseUserBadge[]
         */
        public function getUserNotViewedBadges(User $user);

        /**
         * @param integer $limit
         *
         * @return BaseUserBadge[]
         */
        public function getNewestBadges($limit);
    }