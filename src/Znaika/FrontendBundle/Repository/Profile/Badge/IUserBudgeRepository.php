<?
    namespace Znaika\FrontendBundle\Repository\Profile\Badge;

    use Znaika\FrontendBundle\Entity\Profile\Badge\BaseUserBadge;

    interface IUserBadgeRepository
    {
        /**
         * @param BaseUserBadge $badge
         *
         * @return bool
         */
        public function save(BaseUserBadge $badge);
    }