<?

    namespace Znaika\FrontendBundle\Helper\Content;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Helper\Vimeo;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class ContentThumbnailUpdater
    {
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

            $result = $this->vimeo->call('vimeo.videos.getThumbnailUrls', array('video_id' => $video->getUrl()));


            if ($result->thumbnails && $result->thumbnails->thumbnail && count($result->thumbnails->thumbnail) >= 3)
            {
                $smallThumbnailUrl  = $result->thumbnails->thumbnail[0]->_content;
                $mediumThumbnailUrl = $result->thumbnails->thumbnail[1]->_content;
                $largeThumbnailUrl  = $result->thumbnails->thumbnail[2]->_content;

                $video->setSmallThumbnailUrl($smallThumbnailUrl);
                $video->setMediumThumbnailUrl($mediumThumbnailUrl);
                $video->setLargeThumbnailUrl($largeThumbnailUrl);
            }

        }
    }