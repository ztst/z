<?
    namespace Znaika\LikesBundle\Repository;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\LikesBundle\Entity\VideoCommentLike;
    use Znaika\ProfileBundle\Entity\User;

    interface IVideoCommentLikeRepository
    {
        /**
         * @param VideoCommentLike $videoCommentLike
         *
         * @return mixed
         */
        public function save(VideoCommentLike $videoCommentLike);

        /**
         * @param VideoCommentLike $videoCommentLike
         *
         * @return bool
         */
        public function delete(VideoCommentLike $videoCommentLike);

        /**
         * @param User $user
         * @param VideoComment $videoComment
         *
         * @return VideoCommentLike
         */
        public function getUserCommentLike(User $user, VideoComment $videoComment);

        /**
         * @param User $user
         * @param Video $video
         *
         * @return VideoComment[]
         */
        public function getUserVideoLikedComments(User $user, Video $video);
    }
