<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

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
            return null;
        }
    }