<?php

    namespace Znaika\FrontendBundle\Twig\Video;

    use Znaika\FrontendBundle\Entity\Lesson\Content\Attachment\Quiz;
    use Znaika\FrontendBundle\Entity\Lesson\Content\Video;
    use Znaika\FrontendBundle\Helper\Uploader\QuizUploader;

    class QuizExtension extends \Twig_Extension
    {
        /**
         * @var \Twig_Environment
         */
        private $twig;

        /**
         * @var QuizUploader
         */
        private $quizUploader;

        public function __construct(\Twig_Environment $twig, QuizUploader $quizUploader)
        {
            $this->twig         = $twig;
            $this->quizUploader = $quizUploader;
        }

        public function getFunctions()
        {
            return array(
                'show_quiz' => new \Twig_Function_Method($this, 'renderVideoQuiz'),
            );
        }

        /**
         * @param \Znaika\FrontendBundle\Entity\Lesson\Content\Video $video
         *
         * @return string
         */
        public function renderVideoQuiz(Video $video)
        {
            $quiz = $video->getQuiz();
            $html = $this->prepareUrl($quiz);

            $templateFile    = "ZnaikaFrontendBundle:Video:quiz_container.html.twig";
            $templateContent = $this->twig->loadTemplate($templateFile);
            $result          = $templateContent->render(array("source" => $html));

            return $result;
        }

        private function prepareUrl(Quiz $quiz)
        {
            return "/quiz_content/" . $quiz->getVideo()->getUrlName() . "/index.html";
        }

        /**
         * @return string
         */
        public function getName()
        {
            return 'znaika_quiz_extension';
        }
    }
