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
            $fileDir = $root . self::UPLOAD_PATH . $synopsis->getVideo()->getContentDir() . "/";

            return $fileDir;
        }

        public function getHtmlFilePath(Synopsis $synopsis)
        {
            return $this->getFileDir($synopsis) . $synopsis->getHtmlFileName();
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

                $this->processUnzippedFiles($synopsis);
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

        private function processUnzippedFiles(Synopsis $synopsis)
        {
            $fileDir   = $this->getFileDir($synopsis);
            $htmlFiles = UnixSystemUtils::getDirectoryFiles($fileDir, "/[^\.]+\.(htm|html)/");

            if (count($htmlFiles) == 1)
            {
                $fileName = $htmlFiles[0];
                $synopsis->setHtmlFileName($fileName);

                $this->processHtmlFile($synopsis);
            }
        }

        private function processHtmlFile(Synopsis $synopsis)
        {
            $path = $this->getHtmlFilePath($synopsis);

            $content = UnixSystemUtils::getFileContents($path);
            $synopsis->setText(strip_tags($content));
            $content = $this->prepareStyles($content);
            $content = $this->removeExcessTags($content);
            $content = $this->prepareImagesUrls($synopsis, $content);
            UnixSystemUtils::setFileContents($path, $content);
        }

        private function removeExcessTags($content)
        {
            $patterns = array(
                "/\<html[^>]*>/",
                "/\<\!DOCTYPE[^>]*>/",
                "/\<\/html[^>]*>/",
                "/\<head[^>]*>/",
                "/\<\/head[^>]*>/",
                "/\<body[^>]*>/",
                "/\<\/body[^>]*>/",
                "/\<meta[^>]*>/",
                "/\<\/meta[^>]*>/",
            );
            $content  = preg_replace($patterns, "", $content);

            return $content;
        }

        /**
         * @param Synopsis $synopsis
         * @param $content
         *
         * @return mixed
         */
        private function prepareImagesUrls(Synopsis $synopsis, $content)
        {
            $patterns   = array(
                "/src=\"([^\.]+)\.(png|jpg|jpeg|gif)/",
            );
            $replaceStr = "src=\"/synopsis_content/" . $synopsis->getVideo()->getContentDir() . "/$1.$2";
            $content    = preg_replace($patterns, $replaceStr, $content);

            return $content;
        }

        /**
         * @param $content
         *
         * @return string
         */
        private function prepareStyles($content)
        {
            $doc = new \DOMDocument();
            $doc->loadHTML($content);
            $styles = $doc->getElementsByTagName("style");

            for ($i = 0; $i < $styles->length; $i++)
            {
                $style   = $styles->item($i);
                $content = $style->nodeValue;

                $patterns         = array(
                    "/([\}|,][\s]+)([^,\@\{]+)/"
                );
                $style->nodeValue = preg_replace($patterns, "$1 .synopsis-container $2", $content);
            }
            $content = $doc->saveHTML();

            $pattern = array(
                "/\<style([^>]*)>/",
            );
            $content = preg_replace($pattern, "<style scoped $1>", $content);

            return $content;
        }
    }