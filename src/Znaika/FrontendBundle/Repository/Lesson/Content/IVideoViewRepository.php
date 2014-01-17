<?
    namespace Znaika\FrontendBundle\Repository\Lesson\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoView;

    interface IVideoViewRepository
    {
        /**
         * @param VideoView $videoView
         *
         * @return bool
         */
        public function save(VideoView $videoView);

        /**
         * @param $video
         * @param $user
         *
         * @return VideoView
         */
        public function getOneByVideoAndUser($video, $user);
    }
