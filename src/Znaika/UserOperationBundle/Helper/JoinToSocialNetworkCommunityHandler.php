<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\UserOperationBundle\Entity\JoinSocialNetworkCommunityOperation;
    use Znaika\ProfileBundle\Entity\User;

    class JoinToSocialNetworkCommunityHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user          = $this->getUser();
            $socialNetwork = $this->getSocialNetwork();

            return $this->saveJoinSocialNetworkCommunityOperation($user, $socialNetwork);
        }

        /**
         * @param User $user
         * @param $network
         *
         * @return \Znaika\UserOperationBundle\Entity\JoinSocialNetworkCommunityOperation
         */
        private function saveJoinSocialNetworkCommunityOperation(User $user, $network)
        {
            $operation = $this->userOperationRepository->getLastJoinSocialNetworkCommunityOperation($user, $network);
            if (!$operation)
            {
                $operation = new JoinSocialNetworkCommunityOperation();
                $operation->setUser($user);
                $operation->setSocialNetwork($network);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }
    }