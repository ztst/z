<?
    namespace Znaika\UserOperationBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationPoints;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationType;

    class PostVideoToSocialNetworkOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::POST_VIDEO_TO_SOCIAL_NETWORK)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::POST_VIDEO_TO_SOCIAL_NETWORK;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::POST_VIDEO;
        }
    }