<?

    namespace Znaika\FrontendBundle\Helper\Content;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;
    use Znaika\FrontendBundle\Helper\Vimeo;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoRepository;

    class VideoInfoUpdater
    {
        const UPLOAD_PATH           = "video_thumbnail/";
        const SMALL_THUMBNAIL_WIDTH = 120;

        /**
         * @var VideoRepository
         */
        private $videoRepository;

        /**
         * @var Vimeo
         */
        private $vimeo;
        /**
         * @var ContainerInterface
         */
        private $container;


        public function __construct(VideoRepository $videoRepository, Vimeo $vimeo, ContainerInterface $container)
        {
            $this->videoRepository = $videoRepository;
            $this->vimeo           = $vimeo;
            $this->container       = $container;
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
                $largeThumbnailUrl = $videoInfo->thumbnails->thumbnail[2]->_content;

                $image = new \Imagick($largeThumbnailUrl);

                $root    = $this->container->getParameter('upload_file_dir');
                $fileDir = $root . self::UPLOAD_PATH . $video->getContentDir();
                UnixSystemUtils::createDirectory($fileDir, 0755, true);

                UnixSystemUtils::setFileContents("{$fileDir}/large", $image);

                $width = $image->getimagewidth();
                $height = $image->getimageheight();
                $scaledHeight = self::SMALL_THUMBNAIL_WIDTH * $height / $width;
                $image->scaleimage(self::SMALL_THUMBNAIL_WIDTH,$scaledHeight);
                UnixSystemUtils::setFileContents("{$fileDir}/small", $image);
            }
        }
    }