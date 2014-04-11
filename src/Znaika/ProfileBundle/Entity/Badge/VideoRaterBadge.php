<?
    namespace Znaika\ProfileBundle\Entity\Badge;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserBadgeType;

    class VideoRaterBadge extends BaseUserBadge
    {
        const MIN_RATES = 10;

        /**
         * @param int $badgeType
         *
         * @throws \InvalidArgumentException
         */
        public function setBadgeType($badgeType)
        {
            if ($badgeType != UserBadgeType::VIDEO_RATER)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getBadgeType()
        {
            return UserBadgeType::VIDEO_RATER;
        }
    }