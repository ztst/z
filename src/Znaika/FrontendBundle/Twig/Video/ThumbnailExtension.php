<?
    namespace Znaika\FrontendBundle\Twig\Video;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;

    class ThumbnailExtension extends \Twig_Extension
    {
        public function getFunctions()
        {
            return array(
                'large_video_thumbnail' => new \Twig_Function_Method($this, 'getLargeThumbnailUrl'),
                'small_video_thumbnail' => new \Twig_Function_Method($this, 'getSmallThumbnailUrl'),
            );
        }

        /**
         * @param Video $video
         *
         * @return string
         */
        public function getLargeThumbnailUrl(Video $video)
        {
            $contentDir = $video->getContentDir();
            $result = "/video-thumbnail/{$contentDir}/large";
            return $result;
        }

        /**
         * @param Video $video
         *
         * @return string
         */
        public function getSmallThumbnailUrl(Video $video)
        {
            $contentDir = $video->getContentDir();
            $result = "/video-thumbnail/{$contentDir}/small";
            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_video_thumbnail_extension';
        }
    }
