<?
    namespace Znaika\FrontendBundle\Helper\UserOperation;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation;
    use Znaika\FrontendBundle\Entity\Profile\Badge\CommentWriterBadge;
    use Znaika\FrontendBundle\Entity\Profile\User;

    class AddVideoCommentHandler extends UserOperationHandler
    {
        protected function doHandle()
        {
            $user  = $this->getUser();
            $video = $this->getVideo();

            $operation = $this->saveAddVideoCommentOperation($user, $video);
            $this->saveCommentWriterBadge();

            return $operation;
        }

        /**
         * @param User $user
         * @param Video $video
         *
         * @return \Znaika\FrontendBundle\Entity\Profile\Action\AddVideoCommentOperation
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

        private function saveCommentWriterBadge()
        {
            $user = $this->getUser();
            $countOperations = $this->userOperationRepository->countAddVideoCommentOperations($user);
            if ($countOperations == CommentWriterBadge::MIN_WRITTEN_COMMENTS)
            {
                $badge = new CommentWriterBadge();
                $badge->setUser($user);

                $this->userBadgeRepository->save($badge);
            }
        }
    }