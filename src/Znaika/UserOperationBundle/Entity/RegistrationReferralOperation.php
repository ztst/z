<?
    namespace Znaika\UserOperationBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationPoints;
    use Znaika\UserOperationBundle\Helper\Util\UserOperationType;

    class RegistrationReferralOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::REGISTRATION_REFERRAL_OPERATION)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::REGISTRATION_REFERRAL_OPERATION;
        }

        public function getAccruedPoints()
        {
            return UserOperationPoints::REGISTRATION;
        }
    }