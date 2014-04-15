<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\ViewVideoOperation;
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
         * @return \Znaika\UserOperationBundle\Entity\ViewVideoOperation
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
            }

            return $operation;
        }
    }