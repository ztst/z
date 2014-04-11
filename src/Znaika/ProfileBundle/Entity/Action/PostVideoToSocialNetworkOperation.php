<?
    namespace Znaika\ProfileBundle\Entity\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserOperationPoints;
    use Znaika\ProfileBundle\Helper\Util\UserOperationType;

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