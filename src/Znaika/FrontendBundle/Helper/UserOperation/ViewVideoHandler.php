<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation;
    use Znaika\FrontendBundle\Entity\Profile\Badge\VideoViewerBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class ViewVideoHandler extends UserOperationHandler
    {

        protected function doHandle()
        {
            $user  = $this->getUser();
            $video = $this->getVideo();

            $viewVideoOperation = $this->saveViewVideoOperation($user, $video);

            return $viewVideoOperation;
        }


        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\ViewVideoOperation
         */
        private function saveViewVideoOperation(User $user, Video $video)
        {
            $operation = $this->userOperationRepository->getLastViewVideoOperation($user, $video);
            if (!$operation)
            {
                $views = $video->getViews();
                $video->setViews(++$views);

                $newOperation = new ViewVideoOperation();
                $newOperation->setUser($user);
                $newOperation->setVideo($video);
                $this->userOperationRepository->save($newOperation);

                $this->saveVideoViewerBadge($user);
            }

            return $operation;
        }

        private function saveVideoViewerBadge(User $user)
        {
            $countViewedVideos = $this->userOperationRepository->countViewVideoOperations($user);
            if ($countViewedVideos == VideoViewerBadge::MIN_VIEWED_VIDEOS)
            {
                $badge = new VideoViewerBadge();
                $badge->setUser($user);
                $this->userBadgeRepository->save($badge);
            }
        }
    }