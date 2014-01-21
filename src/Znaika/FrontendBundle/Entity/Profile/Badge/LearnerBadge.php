<?
    namespace Znaika\FrontendBundle\Entity\Profile\Badge;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserBadgeType;

    class LearnerBadge extends BaseUserBadge
    {
        const MIN_POINTS = 300;

        /**
         * @param int $badgeType
         *
         * @throws \InvalidArgumentException
         */
        public function setBadgeType($badgeType)
        {
            if ($badgeType != UserBadgeType::LEARNER_BADGE)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getBadgeType()
        {
            return UserBadgeType::LEARNER_BADGE;
        }
    }