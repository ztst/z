<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\ProfileBundle\Entity\Badge\ReferralInviterBadge;
    use Znaika\ProfileBundle\Entity\User;

    class RegistrationReferralHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user = $this->getUser();

            return $this->saveRegistrationReferralOperation($user);
        }

        /**
         * @param User $user
         *
         * @return null
         */
        private function saveRegistrationReferralOperation(User $user)
        {
            //TODO: add method

            $this->saveReferralInviterBadge($user);
            return null;
        }

        private function saveReferralInviterBadge(User $user)
        {
            $countReferrals = $this->userOperationRepository->countReferralRegistrationOperations($user);
            if ($countReferrals == ReferralInviterBadge::MIN_INVITED_REFERRALS)
            {
                $badge = new ReferralInviterBadge();
                $badge->setUser($user);
                $this->userBadgeRepository->save($badge);
            }
        }
    }