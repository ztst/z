<?
    namespace Znaika\UserOperationBundle\Helper;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\UserOperationBundle\Entity\AddVideoCommentOperation;
    use Znaika\ProfileBundle\Entity\User;

    class AddVideoCommentHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user  = $this->getUser();
            $video = $this->getVideo();

            $operation = $this->saveAddVideoCommentOperation($user, $video);

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\UserOperationBundle\Entity\AddVideoCommentOperation
         */
        private function saveAddVideoCommentOperation(User $user, Video $video)
        {
            $operation = $this->userOperationRepository->getLastAddVideoCommentOperation($user, $video);
            if (!$operation)
            {
                $operation = new AddVideoCommentOperation();
                $operation->setUser($user);
                $operation->setVideo($video);
                $this->userOperationRepository->save($operation);
            }

            return $operation;
        }
    }