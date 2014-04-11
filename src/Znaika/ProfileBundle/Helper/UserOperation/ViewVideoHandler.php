<?
    namespace Znaika\ProfileBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\ProfileBundle\Entity\Action\ViewVideoOperation;
    use Znaika\ProfileBundle\Entity\Badge\VideoViewerBadge;
    use Znaika\ProfileBundle\Entity\User;

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
         * @return \Znaika\ProfileBundle\Entity\Action\ViewVideoOperation
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