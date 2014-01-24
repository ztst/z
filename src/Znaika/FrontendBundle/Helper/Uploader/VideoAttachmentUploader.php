<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\Security\Core\Util\SecureRandom;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\VideoAttachment;

    class VideoAttachmentUploader
    {
        const RANDOM_FILE_NAME_LENGTH = 27;
        const UPLOAD_PATH             = "/video_attachments";

        /**
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        private $container;

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function getAbsoluteFilePath(VideoAttachment $attachment)
        {
            return $this->getFileDir($attachment) . $attachment->getRealName();
        }

        public function upload(VideoAttachment $attachment)
        {
            if (null === $attachment->getFile())
            {
                return;
            }

            $fileName = $this->getFileName($attachment);
            $fileDir  = VideoAttachmentUploader::getFileDir($attachment);
            $attachment->getFile()->move($fileDir, $fileName);

            $attachment->setRealName($fileName);
            $attachment->setName($attachment->getFile()->getClientOriginalName());
        }

        private function getFileDir(VideoAttachment $attachment)
        {
            $root    = $this->container->getParameter('upload_file_dir');
            $fileDir = $root . self::UPLOAD_PATH . "/" . $attachment->getVideo()->getVideoId() . "/";

            return $fileDir;
        }

        private function getFileName(VideoAttachment $attachment)
        {
            return $this->generateFileName() . "." . $attachment->getFile()->getClientOriginalExtension();
        }

        private function generateFileName()
        {
            $generator = new SecureRandom();

            return bin2hex($generator->nextBytes(self::RANDOM_FILE_NAME_LENGTH));
        }
    }