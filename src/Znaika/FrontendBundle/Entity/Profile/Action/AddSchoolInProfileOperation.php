<?
    namespace Znaika\FrontendBundle\Entity\Profile\Action;

    use Doctrine\ORM\Mapping as ORM;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserOperationPoints;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserOperationType;

    class AddSchoolInProfileOperation extends BaseUserOperation
    {
        /**
         * @param int $type
         *
         * @throws \InvalidArgumentException
         */
        public function setOperationType($type)
        {
            if ($type != UserOperationType::ADD_SCHOOL_IN_PROFILE_OPERATION)
            {
                throw new \InvalidArgumentException();
            }
        }

        /**
         * @return int
         */
        public function getOperationType()
        {
            return UserOperationType::ADD_SCHOOL_IN_PROFILE_OPERATION;
        }

        protected function getAccruedPoints()
        {
            return UserOperationPoints::ADD_PROFILE_FIELD;
        }
    }