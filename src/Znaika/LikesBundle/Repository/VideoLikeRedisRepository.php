<?
    namespace Znaika\LikesBundle\Repository;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\ProfileBundle\Entity\User;

    class VideoLikeRedisRepository implements IVideoLikeRepository
    {
        /**
         * {@inheritdoc}
         */
        public function save(VideoLike $videoLike)
        {
            return true;
        }

        /**
         * @param VideoLike $videoLike
         *
         * @return bool
         */
        public function delete(VideoLike $videoLike)
        {
            return true;
        }

        /**
         * {@inheritdoc}
         */
        public function getUserVideoLike(User $user, Video $video)
        {
            // TODO: Implement isUserLikedVideo() method.
        }

        /**
         * {@inheritdoc}
         */
        public function getUserLikedVideos(User $user)
        {
            // TODO: Implement getUserLikedVideos() method.
        }
    }