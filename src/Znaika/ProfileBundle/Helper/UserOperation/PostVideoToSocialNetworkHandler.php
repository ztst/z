<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\Action\PostVideoToSocialNetworkOperation;
    use Znaika\ProfileBundle\Entity\Badge\SocialNetworkPosterBadge;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Helper\Util\SocialNetworkUtil;

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
         * @return null|\Znaika\ProfileBundle\Entity\Action\PostVideoToSocialNetworkOperation
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