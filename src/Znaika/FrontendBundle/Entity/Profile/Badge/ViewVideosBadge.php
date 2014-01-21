<?
    namespace Znaika\FrontendBundle\Entity\Profile\Badge;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBadgeType;

    class ViewVideosBadge extends BaseUserBadge
    {
        /**
         * @param int $badgeType
         *
         * @throws \InvalidArgumentException
         */
        public function setBadgeType($badgeType)
        {
            if ($badgeType != UserBadgeType::VIEW_VIDEOS)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getBadgeType()
        {
            return UserBadgeType::VIEW_VIDEOS;
        }
    }