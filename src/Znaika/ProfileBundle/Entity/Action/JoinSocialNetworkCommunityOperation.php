<?
    namespace Znaika\ProfileBundle\Entity\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserOperationPoints;
    use Znaika\ProfileBundle\Helper\Util\UserOperationType;

    class JoinSocialNetworkCommunityOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::JOIN_SOCIAL_NETWORK_COMMUNITY_OPERATION)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::JOIN_SOCIAL_NETWORK_COMMUNITY_OPERATION;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::REGISTRATION;
        }
    }