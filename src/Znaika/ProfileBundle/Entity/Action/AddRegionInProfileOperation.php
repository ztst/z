<?
    namespace Znaika\ProfileBundle\Entity\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserOperationPoints;
    use Znaika\ProfileBundle\Helper\Util\UserOperationType;

    class AddRegionInProfileOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::ADD_REGION_IN_PROFILE_OPERATION)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::ADD_REGION_IN_PROFILE_OPERATION;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::ADD_PROFILE_FIELD;
        }
    }