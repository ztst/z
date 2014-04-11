<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\ProfileBundle\Entity\Action\JoinSocialNetworkCommunityOperation;
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
         * @return \Znaika\ProfileBundle\Entity\Action\JoinSocialNetworkCommunityOperation
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