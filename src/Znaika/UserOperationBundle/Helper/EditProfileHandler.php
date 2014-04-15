<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\UserOperationBundle\Entity\AddSexInProfileOperation;
    use Znaika\ProfileBundle\Entity\User;

    class EditProfileHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user = $this->getUser();

            $addBirthdayOperation    = $this->saveAddBirthdayInProfileOperation($user);
            $addPhoneNumberOperation = $this->saveAddPhoneNumberInProfileOperation($user);
            $addSexOperation         = $this->saveAddSexInProfileOperation($user);

            $isFilledOutProfile = !is_null($addBirthdayOperation) &&
                !is_null($addPhoneNumberOperation) && !is_null($addSexOperation);
            $this->saveFilledOutProfileOperation($isFilledOutProfile);
        }

        /**
         * @param User $user
         *
         * @return null
         */
        private function saveAddBirthdayInProfileOperation(User $user)
        {
            //TODO: add method
            return null;
        }

        /**
         * @param User $user
         *
         * @return null
         */
        private function saveAddPhoneNumberInProfileOperation(User $user)
        {
            //TODO: add method
            return null;
        }

        /**
         * @param User $user
         *
         * @return null|\Znaika\UserOperationBundle\Entity\AddSexInProfileOperation
         */
        private function saveAddSexInProfileOperation(User $user)
        {
            if (!$user->getSex())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddSexInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddSexInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        private function saveFilledOutProfileOperation($isFilledOutProfile)
        {
            if ($isFilledOutProfile)
            {
            }
        }
    }