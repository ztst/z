<?
    namespace Znaika\UserOperationBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationPoints;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationType;

    class ViewVideoOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::VIEW_VIDEO)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::VIEW_VIDEO;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::VIEW_VIDEO;
        }
    }