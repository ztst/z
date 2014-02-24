<?
    namespace Znaika\FrontendBundle\Twig\Video;

    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;

    class CommentExtenstion extends \Twig_Extension
    {
        /**
         * @var \Twig_Environment
         */
        private $twig;

        public function __construct(\Twig_Environment $twig)
        {
            $this->twig = $twig;
        }

        public function getFunctions()
        {
            return array(
                'comment_title' => new \Twig_Function_Method($this, 'renderCommentTitle'),
            );
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $comment
         *
         * @return string
         */
        public function renderCommentTitle(VideoComment $comment)
        {
            $result = "";
            $type   = $comment->getCommentType();
            switch ($type)
            {
                case VideoCommentUtil::QUESTION:
                    $templateFile    = "ZnaikaFrontendBundle:Video:question_comment_title.html.twig";
                    $templateContent = $this->twig->loadTemplate($templateFile);
                    $result          = $templateContent->render(array("comment" => $comment));
                    break;
                case VideoCommentUtil::SIMPLE_COMMENT:
                default:
                    $templateFile    = "ZnaikaFrontendBundle:Video:simple_comment_title.html.twig";
                    $templateContent = $this->twig->loadTemplate($templateFile);
                    $result          = $templateContent->render(array("comment" => $comment));
                    break;
            }

            return $result;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_video_comment_extension';
        }
    }
