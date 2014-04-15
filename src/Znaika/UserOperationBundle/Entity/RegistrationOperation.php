<?
    namespace Znaika\UserOperationBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationPoints;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationType;

    class RegistrationOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::REGISTRATION_OPERATION)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::REGISTRATION_OPERATION;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::REGISTRATION;
        }
    }