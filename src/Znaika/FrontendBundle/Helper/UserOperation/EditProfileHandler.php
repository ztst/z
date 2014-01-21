<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Profile\Action\AddCityInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddSexInProfileOperation;
    use Znaika\FrontendBundle\Entity\Profile\Badge\FilledOutProfileBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class EditProfileHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user = $this->getUser();

            $addCityOperation        = $this->saveAddCityInProfileOperation($user);
            $addBirthdayOperation    = $this->saveAddBirthdayInProfileOperation($user);
            $addClassroomOperation   = $this->saveAddClassroomInProfileOperation($user);
            $addPhoneNumberOperation = $this->saveAddPhoneNumberInProfileOperation($user);
            $addSchoolOperation      = $this->saveAddSchoolInProfileOperation($user);
            $addSexOperation         = $this->saveAddSexInProfileOperation($user);

            $isFilledOutProfile = !is_null($addCityOperation) && !is_null($addBirthdayOperation) &&
                !is_null($addClassroomOperation) && !is_null($addPhoneNumberOperation) &&
                !is_null($addSchoolOperation) && !is_null($addSexOperation);
            $this->saveFilledOutProfileBadge($isFilledOutProfile);
        }


        /**
         * @param User $user
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\AddCityInProfileOperation
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
         * @return null
         */
        private function saveAddSchoolInProfileOperation(User $user)
        {
            //TODO: add method
            return null;
        }

        /**
         * @param User $user
         *
         * @return null
         */
        private function saveAddClassroomInProfileOperation(User $user)
        {
            //TODO: add method
            return null;
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
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\AddSexInProfileOperation
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
    }