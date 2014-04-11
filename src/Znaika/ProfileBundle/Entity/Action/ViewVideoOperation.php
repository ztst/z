<?
    namespace Znaika\ProfileBundle\Entity\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserOperationPoints;
    use Znaika\ProfileBundle\Helper\Util\UserOperationType;

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