<?
    namespace Znaika\UserOperationBundle\Helper;

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

            return null;
        }
    }