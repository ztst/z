<?
    namespace Znaika\ProfileBundle\Entity\Badge;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserBadgeType;

    class FilledOutProfileBadge extends BaseUserBadge
    {
        /**
         * @param int $badgeType
         *
         * @throws \InvalidArgumentException
         */
        public function setBadgeType($badgeType)
        {
            if ($badgeType != UserBadgeType::FILLED_OUT_PROFILE)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getBadgeType()
        {
            return UserBadgeType::FILLED_OUT_PROFILE;
        }
    }