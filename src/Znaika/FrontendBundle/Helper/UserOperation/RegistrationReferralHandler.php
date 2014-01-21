<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Profile\Badge\ReferralInviterBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

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