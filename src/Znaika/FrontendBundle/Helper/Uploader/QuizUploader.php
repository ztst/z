<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;
    use Symfony\Component\Security\Core\Util\SecureRandom;

    class QuizUploader
    {
        const UPLOAD_PATH             = "quiz/";
        const RANDOM_FILE_NAME_LENGTH = 27;

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

            $quiz->setLocationName($this->generateDirName());
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
            $fileDir = $root . self::UPLOAD_PATH . $quiz->getLocationName() . "/";

            return $fileDir;
        }

        private function generateDirName()
        {
            $generator = new SecureRandom();

            return bin2hex($generator->nextBytes(self::RANDOM_FILE_NAME_LENGTH));
        }
    }