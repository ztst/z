<?
    namespace Znaika\LikesBundle\Repository;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\LikesBundle\Entity\VideoCommentLike;
    use Znaika\ProfileBundle\Entity\User;

    class VideoCommentLikeRedisRepository implements IVideoCommentLikeRepository
    {
        /**
         * {@inheritdoc}
         */
        public function save(VideoCommentLike $videoCommentLike)
        {
            return true;
        }

        /**
         * @param VideoCommentLike $videoCommentLike
         *
         * @return bool
         */
        public function delete(VideoCommentLike $videoCommentLike)
        {
            return true;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserCommentLike(User $user, VideoComment $videoComment)
        {
            return null;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserVideoLikedComments(User $user, Video $video)
        {
            return null;
        }
    }