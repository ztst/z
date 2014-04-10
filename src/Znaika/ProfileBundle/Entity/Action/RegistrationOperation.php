<?
    namespace Znaika\ProfileBundle\Entity\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\ProfileBundle\Helper\Util\UserOperationPoints;
    use Znaika\ProfileBundle\Helper\Util\UserOperationType;

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