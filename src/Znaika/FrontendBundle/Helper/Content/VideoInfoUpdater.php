<?

    namespace Znaika\FrontendBundle\Helper\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Helper\Vimeo;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class VideoInfoUpdater
    {
        const SMALL_THUMBNAIL_WIDTH = 100;

        /**
         * @var VideoRepository
         */
        private $videoRepository;

        /**
         * @var Vimeo
         */
        private $vimeo;

        public function __construct(VideoRepository $videoRepository, Vimeo $vimeo)
        {
            $this->videoRepository = $videoRepository;
            $this->vimeo           = $vimeo;
        }

        /**
         * Loads thumbnail urls from vimeo
         *
         * @param Video $video
         */
        public function update(Video $video)
        {
            if (null === $video->getUrl())
            {
                return;
            }

            $result = $this->vimeo->call('vimeo.videos.getInfo', array('video_id' => $video->getUrl()));

            if ($result && $result->stat == "ok")
            {
                if (is_array($result->video) && count($result->video) > 0)
                {
                    $videoInfo = $result->video[0];
                    $video->setDuration($videoInfo->duration);
                    $this->updateThumbnails($video, $videoInfo);
                }
            }

        }

        /**
         * @param Video $video
         * @param $videoInfo
         */
        private function updateThumbnails(Video $video, $videoInfo)
        {
            if ($videoInfo->thumbnails && $videoInfo->thumbnails->thumbnail && count($videoInfo->thumbnails->thumbnail) >= 3)
            {
                $smallThumbnailUrl  = $videoInfo->thumbnails->thumbnail[0]->_content;
                $mediumThumbnailUrl = $videoInfo->thumbnails->thumbnail[1]->_content;
                $largeThumbnailUrl  = $videoInfo->thumbnails->thumbnail[2]->_content;

                $video->setSmallThumbnailUrl($smallThumbnailUrl);
                $video->setMediumThumbnailUrl($mediumThumbnailUrl);
                $video->setLargeThumbnailUrl($largeThumbnailUrl);
            }
        }
    }