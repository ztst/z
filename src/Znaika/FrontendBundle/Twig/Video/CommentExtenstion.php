<?
    namespace Znaika\FrontendBundle\Twig\Video;

    use Symfony\Component\Form\FormFactoryInterface;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\FrontendBundle\Entity\Profile\User;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoCommentType;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;
    use Znaika\FrontendBundle\Helper\Util\Profile\UserRole;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;

    class CommentExtenstion extends \Twig_Extension
    {
        /**
         * @var \Twig_Environment
         */
        private $twig;

        /**
         * @var SecurityContextInterface
         */
        private $context;

        /**
         * @var VideoCommentRepository
         */
        private $videoCommentRepository;

        /**
         * @var \Symfony\Component\Form\FormFactoryInterface
         */
        private $formFactory;

        public function __construct(\Twig_Environment $twig, SecurityContextInterface $context = null, VideoCommentRepository $videoCommentRepository, FormFactoryInterface $formFactory)
        {
            $this->formFactory            = $formFactory;
            $this->twig                   = $twig;
            $this->context                = $context;
            $this->videoCommentRepository = $videoCommentRepository;
        }

        public function getFunctions()
        {
            return array(
                'comment_title'                 => new \Twig_Function_Method($this, 'renderCommentTitle'),
                'has_question_for_current_user' => new \Twig_Function_Method($this, 'hasQuestionForCurrentUser'),
                'video_questions_block'         => new \Twig_Function_Method($this, 'renderVideoQuestions'),
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
                case VideoCommentUtil::ANSWER:
                    $templateFile    = "ZnaikaFrontendBundle:Video:answer_comment_title.html.twig";
                    $templateContent = $this->twig->loadTemplate($templateFile);
                    $result          = $templateContent->render(array("comment" => $comment));
                    break;
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
         * @param Video $video
         *
         * @return string
         */
        public function renderVideoQuestions(Video $video)
        {
            $videoComment        = new VideoComment();
            $addVideoCommentForm = $this->formFactory->create(new VideoCommentType(), $videoComment);

            $questions       = $this->videoCommentRepository->getVideoNotAnsweredQuestionComments($video);
            $templateFile    = "ZnaikaFrontendBundle:Video:video_questions.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array(
                "questions"           => $questions,
                "addVideoCommentForm" => $addVideoCommentForm->createView(),
                "video"               => $video,
            ));

            return $result;
        }

        /**
         * @param Video $video
         *
         * @return bool
         */
        public function hasQuestionForCurrentUser(Video $video)
        {
            if (null === $this->context)
            {
                return false;
            }

            if (!$this->context->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_TEACHER)))
            {
                return false;
            }

            $videoSupervisors = $video->getSupervisors();
            $user             = $this->getUser();

            if (!$videoSupervisors->contains($user) || $this->context->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_MODERATOR)))
            {
                return false;
            }

            return count($this->videoCommentRepository->getVideoNotAnsweredQuestionComments($video)) > 0;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_video_comment_extension';
        }

        /**
         * @return User
         */
        private function getUser()
        {
            return $this->context->getToken()->getUser();
        }
    }
