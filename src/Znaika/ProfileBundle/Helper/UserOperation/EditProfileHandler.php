<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\ProfileBundle\Entity\Action\AddSexInProfileOperation;
    use Znaika\ProfileBundle\Entity\Badge\FilledOutProfileBadge;
    use Znaika\ProfileBundle\Entity\User;

    class EditProfileHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user = $this->getUser();

            $addBirthdayOperation    = $this->saveAddBirthdayInProfileOperation($user);
            $addPhoneNumberOperation = $this->saveAddPhoneNumberInProfileOperation($user);
            $addSexOperation         = $this->saveAddSexInProfileOperation($user);
            $addRegionOperation      = $this->saveAddRegionInProfileOperation($user);

            $isFilledOutProfile = !is_null($addBirthdayOperation) &&
                !is_null($addPhoneNumberOperation) && !is_null($addSexOperation) &&
                !is_null($addRegionOperation);
            $this->saveFilledOutProfileBadge($isFilledOutProfile);
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
         * @return null|\Znaika\ProfileBundle\Entity\Action\AddSexInProfileOperation
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

        private function saveFilledOutProfileBadge($isFilledOutProfile)
        {
            if ($isFilledOutProfile)
            {
                $badge = new FilledOutProfileBadge();
                $badge->setUser($this->getUser());

                $this->userBadgeRepository->save($badge);
            }
        }

        /**
         * @param User $user
         *
         * @return null|\Znaika\ProfileBundle\Entity\Action\AddRegionInProfileOperation
         */
        private function saveAddRegionInProfileOperation(User $user)
        {
            if (!$user->getSex())
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastAddRegionInProfileOperation($user);
            if (!$operation)
            {
                $operation = new AddSexInProfileOperation();
                $operation->setUser($user);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }
    }