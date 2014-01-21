<?
    namespace Znaika\FrontendBundle\Entity\Profile\Badge;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBadgeType;

    class ReferralInviterBadge extends BaseUserBadge
    {
        const MIN_INVITED_REFERRALS = 10;

        /**
         * @param int $badgeType
         *
         * @throws \InvalidArgumentException
         */
        public function setBadgeType($badgeType)
        {
            if ($badgeType != UserBadgeType::REFERRAL_INVITER)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getBadgeType()
        {
            return UserBadgeType::REFERRAL_INVITER;
        }
    }