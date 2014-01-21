<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\Badge\VideoRaterBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class RateVideoHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user  = $this->getUser();
            $video = $this->getVideo();

            return $this->saveRateVideoOperation($user, $video);
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation
         */
        private function saveRateVideoOperation(User $user, Video $video)
        {
            $operation = $this->userOperationRepository->getLastRateVideoOperation($user, $video);
            if (!$operation)
            {
                $operation = new RateVideoOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $this->userOperationRepository->save($operation);

                $this->saveVideoRateBadge($user);
            }

            return $operation;
        }

        private function saveVideoRateBadge(User $user)
        {
            $countRatedVideos = $this->userOperationRepository->countRateVideoOperations($user);
            if ($countRatedVideos == VideoRaterBadge::MIN_RATES)
            {
                $badge = new VideoRaterBadge();
                $badge->setUser($user);
                $this->userBadgeRepository->save($badge);
            }
        }
    }