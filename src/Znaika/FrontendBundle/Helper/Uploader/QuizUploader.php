<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;

    class QuizUploader
    {
        const UPLOAD_PATH = "/quiz";

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

            $fileDir  = $this->getFileDir($quiz);

            $zip = new \ZipArchive();
            $res = $zip->open($quiz->getFile()->getRealPath());
            if ($res === true)
            {
                $zip->extractTo("$fileDir/");
                $zip->close();
            }

        }

        private function getFileDir(Quiz $quiz)
        {
            $root    = $this->container->getParameter('upload_file_dir');
            $fileDir = $root . self::UPLOAD_PATH . "/" . $quiz->getVideo()->getVideoId() . "/";

            return $fileDir;
        }
    }