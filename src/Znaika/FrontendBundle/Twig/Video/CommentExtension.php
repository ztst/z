<?
    namespace Znaika\FrontendBundle\Twig\Video;

    use Symfony\Component\Form\FormFactoryInterface;
    use Symfony\Component\Security\Core\SecurityContextInterface;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment;
    use Znaika\ProfileBundle\Entity\User;
    use Znaika\FrontendBundle\Form\Lesson\Content\VideoCommentType;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentStatus;
    use Znaika\FrontendBundle\Helper\Util\Lesson\VideoCommentUtil;
    use Znaika\ProfileBundle\Helper\Util\UserRole;
    use Znaika\FrontendBundle\Repository\Lesson\Content\VideoCommentRepository;

    class CommentExtension extends \Twig_Extension
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
                'comment_title'                     => new \Twig_Function_Method($this, 'renderCommentTitle'),
                'comment_text'                      => new \Twig_Function_Method($this, 'renderCommentText'),
                'has_question_for_current_user'     => new \Twig_Function_Method($this, 'hasQuestionForCurrentUser'),
                'video_questions_block'             => new \Twig_Function_Method($this, 'renderVideoQuestions'),
                'count_video_questions'             => new \Twig_Function_Method($this, 'countVideoQuestions'),
                'count_video_not_verified_comments' => new \Twig_Function_Method($this, 'countVideoNotVerifiedComments'),
                'teacher_profile_questions'         => new \Twig_Function_Method($this, 'renderTeacherProfileQuestions'),
                'moderator_profile_comments'        => new \Twig_Function_Method($this, 'renderModeratorProfileComments'),
                'question_answer_form'              => new \Twig_Function_Method($this, 'renderQuestionAnswerForm'),
                'count_video_comments'              => new \Twig_Function_Method($this, 'countVideoComments'),
                'comment_answer_block'              => new \Twig_Function_Method($this, 'renderCommentAnswer'),
            );
        }

        public function renderCommentAnswer(VideoComment $comment)
        {
            if ($comment->getCommentType() != VideoCommentUtil::QUESTION)
            {
                return "";
            }
            $answers = $comment->getAnswers();
            if ($answers->isEmpty())
            {
                return "";
            }

            /** @var VideoComment $answer */
            $answer = $answers->first();

            $templateFile    = "ZnaikaFrontendBundle:Video:comment_answer.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array(
                "comment" => $answer
            ));

            return $result;
        }

        public function countVideoComments(Video $video)
        {
            return $this->videoCommentRepository->countVideoComments($video);
        }

        public function countVideoQuestions(Video $video)
        {
            $count = count($this->videoCommentRepository->getVideoNotAnsweredQuestionComments($video));

            return $count > 0 ? "+$count" : "";
        }

        public function countVideoNotVerifiedComments(Video $video)
        {
            $count = count($this->videoCommentRepository->getVideoNotVerifiedComments($video));

            return $count > 0 ? "+$count" : "";
        }

        public function renderQuestionAnswerForm(VideoComment $comment)
        {
            $videoComment        = new VideoComment();
            $addVideoCommentForm = $this->formFactory->create(new VideoCommentType(), $videoComment);

            $templateFile    = "ZnaikaProfileBundle:Default:question_answer_form.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array(
                "form"    => $addVideoCommentForm->createView(),
                "comment" => $comment,
            ));

            return $result;
        }

        public function renderTeacherProfileQuestions(Video $video)
        {
            $questions       = $this->videoCommentRepository->getVideoNotAnsweredQuestionComments($video);
            $templateFile    = "ZnaikaProfileBundle:Default:video_questions_list.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array(
                "questions" => $questions,
                "video"     => $video,
            ));

            return $result;
        }


        public function renderModeratorProfileComments(Video $video)
        {
            $comments        = $this->videoCommentRepository->getVideoNotVerifiedComments($video);
            $templateFile    = "ZnaikaProfileBundle:Default:video_not_verified_comment_list.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array(
                "comments" => $comments,
                "video"    => $video,
            ));

            return $result;
        }

        public function renderCommentText(VideoComment $comment)
        {
            $result = $comment->getText();
            if ($comment->getStatus() == VideoCommentStatus::DELETED)
            {
                $result = "Комментарий удален. Причина: нарушение положений Пользовательского соглашения";
            }

            return $result;
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\VideoComment $comment
         *
         * @return string
         */
        public function renderCommentTitle(VideoComment $comment)
        {
            $type = $comment->getCommentType();
            switch ($type)
            {
                case VideoCommentUtil::ANSWER:
                    $templateFile = "ZnaikaFrontendBundle:Video:answer_comment_title.html.twig";
                    break;
                case VideoCommentUtil::QUESTION:
                    $templateFile = "ZnaikaFrontendBundle:Video:question_comment_title.html.twig";
                    break;
                case VideoCommentUtil::SIMPLE_COMMENT:
                default:
                    $templateFile = "ZnaikaFrontendBundle:Video:simple_comment_title.html.twig";
                    break;
            }
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("comment" => $comment));

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

            if (!$videoSupervisors->contains($user) && !$this->context->isGranted(UserRole::getSecurityTextByRole(UserRole::ROLE_MODERATOR)))
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
