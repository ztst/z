<?
    namespace Znaika\FrontendBundle\Helper\Uploader;

    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;

    class SynopsisUploader
    {
        const UPLOAD_PATH = "synopsis/";

        /**
         * @var \Symfony\Component\DependencyInjection\ContainerInterface
         */
        private $container;

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function upload(Synopsis $synopsis)
        {
            $fileDir = $this->getFileDir($synopsis);
            UnixSystemUtils::clearDirectory($fileDir);

            $this->uploadHtmlZipFile($synopsis);
            $this->uploadMSFile($synopsis);
        }

        public function getFileDir(Synopsis $synopsis)
        {
            $root    = $this->container->getParameter('upload_file_dir');
            $fileDir = $root . self::UPLOAD_PATH . $synopsis->getVideo()->getUrlName() . "/";

            return $fileDir;
        }

        public function getHtmlFilePath(Synopsis $synopsis)
        {
            return $this->getFileDir($synopsis) . "/" .$synopsis->getName();
        }

        private function uploadHtmlZipFile(Synopsis $synopsis)
        {
            if (null === $synopsis->getHtmlFile())
            {
                return;
            }

            $fileDir = $this->getFileDir($synopsis);
            $zip     = new \ZipArchive();
            $res     = $zip->open($synopsis->getHtmlFile()->getRealPath());
            if ($res === true)
            {
                $zip->extractTo("$fileDir/");
                $zip->close();

                $this->prepareSynopsisFileName($synopsis);
            }
        }

        private function uploadMSFile(Synopsis $synopsis)
        {
            $file = $synopsis->getMsWordFile();
            if (null === $file)
            {
                return;
            }

            $fileDir  = $this->getFileDir($synopsis);
            $fileName = $file->getClientOriginalName();
            $file->move($fileDir, $fileName);
        }

        private function prepareSynopsisFileName(Synopsis $synopsis)
        {
            $fileDir   = $this->getFileDir($synopsis);
            $htmlFiles = UnixSystemUtils::getDirectoryFiles($fileDir, "/[^\.]+\.(htm|html)/");
            if (count($htmlFiles) == 1)
            {
                $synopsis->setName($htmlFiles[0]);

                $this->processHtmlFile($synopsis);
            }
        }

        private function processHtmlFile(Synopsis $synopsis)
        {
            $path = $this->getHtmlFilePath($synopsis);

            $content = UnixSystemUtils::getFileContents($path);

            $patterns = array(
                "/\<html[^>]*>/",
                "/\<\/html[^>]*>/",
                "/\<head[^>]*>/",
                "/\<\/head[^>]*>/",
                "/\<body[^>]*>/",
                "/\<\/body[^>]*>/",
                "/\<meta[^>]*>/",
                "/\<\/meta[^>]*>/",
            );
            $content = preg_replace($patterns, "", $content);

            $patterns = array(
                "/\<style([^>]*)>/",
            );
            $content = preg_replace($patterns, "<style scoped $1>", $content);

            $patterns = array(
                "/src=\"([^\.]+)\.(png|jpg|jpeg|gif)/",
            );
            $replaceStr = "src=\"/synopsis_content/" . $synopsis->getVideo()->getUrlName() . "/$1.$2";
            $content = preg_replace($patterns, $replaceStr, $content);

            UnixSystemUtils::setFileContents($path, $content);
        }
    }