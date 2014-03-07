<?php

    namespace Znaika\FrontendBundle\Twig\Video;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Synopsis;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Helper\Uploader\SynopsisUploader;
    use Znaika\FrontendBundle\Helper\Util\FileSystem\UnixSystemUtils;

    class SynopsisExtension extends \Twig_Extension
    {
        /**
         * @var \Twig_Environment
         */
        private $twig;

        /**
         * @var SynopsisUploader
         */
        private $synopsisUploader;

        public function __construct(\Twig_Environment $twig, SynopsisUploader $synopsisUploader)
        {
            $this->twig             = $twig;
            $this->synopsisUploader = $synopsisUploader;
        }

        public function getFunctions()
        {
            return array(
                'show_synopsis' => new \Twig_Function_Method($this, 'renderVideoSynopsis'),
            );
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         *
         * @return string
         */
        public function renderVideoSynopsis(Video $video)
        {
            $synopsis = $video->getSynopsis();
            $source   = $this->getSource($synopsis);

            $templateFile    = "ZnaikaFrontendBundle:Video:synopsis_container.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("source" => $source));

            return $result;
        }

        private function getSource(Synopsis $synopsis)
        {
            $path = $this->synopsisUploader->getFileDir($synopsis) . "/" . $synopsis->getName();
            return UnixSystemUtils::getFileContents($path);
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_synopsis_extension';
        }
    }
