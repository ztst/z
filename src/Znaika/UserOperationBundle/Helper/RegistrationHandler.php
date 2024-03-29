<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\UserOperationBundle\Entity\RegistrationOperation;
    use Znaika\ProfileBundle\Entity\User;

    class RegistrationHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user = $this->getUser();

            return $this->saveRegistrationOperation($user);
        }

        /**
         * @param User $user
         *
         * @return \Znaika\UserOperationBundle\Entity\RegistrationOperation
         */
        private function saveRegistrationOperation(User $user)
        {
            $operation = $this->userOperationRepository->getLastRegistrationOperation($user);
            if (!$operation)
            {
                $operation = new RegistrationOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }
    }