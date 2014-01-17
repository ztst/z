<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoView;

    class VideoViewRedisRepository implements IVideoViewRepository
    {
        /**
         * @param VideoView $videoView
         *
         * @return mixed
         */
        public function save(VideoView $videoView)
        {
            return true;
        }

        /**
         * @param $video
         * @param $user
         *
         * @return VideoView
         */
        public function getOneByVideoAndUser($video, $user)
        {
            return null;
        }
    }