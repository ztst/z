<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\RateVideoOperation;
    use Znaika\ProfileBundle\Entity\User;

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
         * @return \Znaika\UserOperationBundle\Entity\RateVideoOperation
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