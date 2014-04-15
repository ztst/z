<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\UserOperationBundle\Entity\AddBirthdayInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddCityInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddFirstNameInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddLastNameInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddPhotoInProfileOperation;
    use Znaika\UserOperationBundle\Entity\AddSexInProfileOperation;
    use Znaika\ProfileBundle\Entity\User;

    class EditProfileHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user = $this->getUser();

            $addBirthdayOperation  = $this->saveAddBirthdayInProfileOperation($user);
            $addFirstNameOperation = $this->saveAddFirstNameInProfileOperation($user);
            $addLastNameOperation  = $this->saveAddLastNameInProfileOperation($user);
            $addCityOperation      = $this->saveAddCityInProfileOperation($user);
            $addSexOperation       = $this->saveAddSexInProfileOperation($user);
            $addPhotoOperation     = $this->saveAddPhotoInProfileOperation($user);

            $isFilledOutProfile = !is_null($addBirthdayOperation) &&
                !is_null($addFirstNameOperation) && !is_null($addSexOperation) &&
                !is_null($addLastNameOperation) && !is_null($addCityOperation) &&
                !is_null($addPhotoOperation);
            $this->saveFilledOutProfileOperation($isFilledOutProfile);
        }

        /**
         * @param User $user
         *
         * @return null
         */
        private function saveAddBirthdayInProfileOperation(User $user)
        {
            if (!$user->getBirthDate())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddBirthdayInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddBirthdayInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         *
         * @return AddCityInProfileOperation
         */
        private function saveAddPhotoInProfileOperation(User $user)
        {
            if (!$user->getPhotoFileName())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddPhotoInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddPhotoInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         *
         * @return AddCityInProfileOperation
         */
        private function saveAddCityInProfileOperation(User $user)
        {
            if (!$user->getCity())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddCityInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddCityInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         *
         * @return AddFirstNameInProfileOperation
         */
        private function saveAddFirstNameInProfileOperation(User $user)
        {
            if (!$user->getFirstName())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddFirstNameInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddFirstNameInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }

        /**
         * @param User $user
         *
         * @return AddLastNameInProfileOperation
         */
        private function saveAddLastNameInProfileOperation(User $user)
        {
            if (!$user->getLastName())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddLastNameInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddLastNameInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
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