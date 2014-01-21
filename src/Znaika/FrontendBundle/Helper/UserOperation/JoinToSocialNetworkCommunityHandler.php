<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation;
    use Znaika\FrontendBundle\Entity\Profile\User;

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
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\JoinSocialNetworkCommunityOperation
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