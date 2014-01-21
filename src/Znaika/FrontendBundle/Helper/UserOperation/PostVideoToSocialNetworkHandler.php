<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\PostVideoToSocialNetworkOperation;
    use Znaika\FrontendBundle\Entity\Profile\Badge\SocialNetworkPosterBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Helper\Util\Lesson\SocialNetworkUtil;

    class PostVideoToSocialNetworkHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user          = $this->getUser();
            $video         = $this->getVideo();
            $socialNetwork = $this->getSocialNetwork();

            return $this->savePostVideoToSocialNetworkOperation($user, $video, $socialNetwork);
        }

        /**
         * @param User $user
         * @param Video $video
         * @param $socialNetwork
         *
         * @return null|\Znaika\FrontendBundle\Entity\Profile\Action\PostVideoToSocialNetworkOperation
         */
        private function savePostVideoToSocialNetworkOperation(User $user, Video $video, $socialNetwork)
        {
            if (!SocialNetworkUtil::isValidSocialNetwork($socialNetwork))
            {
                return null;
            }

            $operation = $this->userOperationRepository->getLastPostVideoToSocialNetworkOperation($user, $video,
                $socialNetwork);
            if (!$operation)
            {
                $operation = new PostVideoToSocialNetworkOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $operation->setSocialNetwork($socialNetwork);
                $this->userOperationRepository->save($operation);

                $this->saveSocialNetworkPosterBadge($user);
            }

            return $operation;
        }

        private function saveSocialNetworkPosterBadge(User $user)
        {
            $countPosts = $this->userOperationRepository->countPostVideoToSocialNetworkOperations($user);
            if ($countPosts == SocialNetworkPosterBadge::MIN_POSTS)
            {
                $badge = new SocialNetworkPosterBadge();
                $badge->setUser($user);
                $this->userBadgeRepository->save($badge);
            }
        }
    }