<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\RateVideoOperation;
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
            }

            return $operation;
        }
    }