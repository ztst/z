<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;

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
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RegistrationOperation
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