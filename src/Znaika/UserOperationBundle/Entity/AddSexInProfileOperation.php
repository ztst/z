<?
    namespace Znaika\UserOperationBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationPoints;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationType;

    class AddSexInProfileOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::ADD_SEX_IN_PROFILE_OPERATION)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::ADD_SEX_IN_PROFILE_OPERATION;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::ADD_PROFILE_FIELD;
        }
    }