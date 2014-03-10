<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;

    class QuizUploader
    {
        const UPLOAD_PATH = "quiz/";

        /**
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        private $container;

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function upload(Quiz $quiz)
        {
            if (null === $quiz->getFile())
            {
                return;
            }

            $fileDir = $this->getFileDir($quiz);
            UnixSystemUtils::clearDirectory($fileDir);

            $zip = new \ZipArchive();
            $res = $zip->open($quiz->getFile()->getRealPath());
            if ($res === true)
            {
                $zip->extractTo("$fileDir/");
                $zip->close();
            }

        }

        public function getFileDir(Quiz $quiz)
        {
            $root    = $this->container->getParameter('upload_file_dir');
            $fileDir = $root . self::UPLOAD_PATH . $quiz->getVideo()->getContentDir() . "/";

            return $fileDir;
        }
    }