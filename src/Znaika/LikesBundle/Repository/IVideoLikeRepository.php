<?
    namespace Znaika\LikesBundle\Repository;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\LikesBundle\Entity\VideoLike;
    use Znaika\ProfileBundle\Entity\User;

    interface IVideoLikeRepository
    {
        /**
         *
         * @param VideoLike $videoLike
         *
         * @return mixed
         */
        public function save(VideoLike $videoLike);

        /**
         * @param VideoLike $videoLike
         *
         * @return bool
         */
        public function delete(VideoLike $videoLike);

        /**
         * @param User $user
         * @param Video $video
         *
         * @return VideoLike
         */
        public function getUserVideoLike(User $user, Video $video);

        /**
         * @param User $user
         *
         * @return Video[]
         */
        public function getUserLikedVideos(User $user);
    }
